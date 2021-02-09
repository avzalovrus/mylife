<?php

namespace Component;

/**
 * Класс Router
 * Компонент для работы с маршрутами
 */
class Router
{

    /**
     * Свойство для хранения массива роутов
     * @var array
     */
    private $routes;

    /**
     * Конструктор
     */
    public function __construct()
    {
        // Путь к файлу с роутами
        $routesPath = App::$path . '/config/routes.php';

        // Получаем роуты из файла
        $this->routes = include($routesPath);
    }

    /**
     * Возвращает строку запроса
     */
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            $url_request_app = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
            
            if(array_key_exists($url_request_app[0], App::$apps) && $url_request_app[0] !== ''){
                array_shift($url_request_app);
                return $this->sortURI(trim(implode('/', $url_request_app), '/'));
            }
            return $this->sortURI(trim($_SERVER['REQUEST_URI'], '/'));
        }
    }

    /**
     * Метод для удаления параметра из url и добавления параметров в объект приложения
     */
    private function sortURI($url)
    {
        $uri = explode('?', $url)[0];
        App::$url = $uri;
        App::$get = $_GET;
        App::$post = $_POST;
        App::$files = $_FILES;
        return $uri;
    }

    /**
     * Метод для обработки запроса
     */
    public function run()
    {

        // Получаем строку запроса
        $uri = $this->getURI();

        // Проверка CSRF
        if(!$this->validationCSRF()){
            throw new \Exception("Не пройдена верификация.");
        }

        // Найдена ли страница
        $found = false;

        // Проверяем наличие запроса в массиве маршрутов (routes.php)
        foreach ($this->routes as $uriPattern => $path) {
            if (preg_match("~^$uriPattern$~", $uri)) {
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
                $segments = explode('/', $internalRoute);
                $controllerName = array_shift($segments) . 'Controller';
                $controllerName = ucfirst($controllerName);
                $actionName = 'action' . ucfirst(array_shift($segments));
                $parameters = $segments;
                $found = $this->useControllerAction($controllerName, $actionName, $parameters);
                if ($found) {
                    break;
                }
            }
        }

        // Поиск методов по контроллерам
        if (!$found) {
            $segments = explode('/', $uri);
            if($this->checkDefaultContoller($segments[0])){
                $controllerName = 'Site' . 'Controller';
                $actionName = 'action' . ucfirst(array_shift($segments));
                $parameters = $segments;
                $found = $this->useControllerAction($controllerName, $actionName, $parameters);
            }
            else{
                $controllerName = array_shift($segments);
                if(empty($controllerName)){
                    $controllerName = ucfirst('site' . 'Controller');
                }else{
                    $controllerName = ucfirst($controllerName . 'Controller');
                }
                $actionName = 'action' . ucfirst(array_shift($segments));
                if('action' === $actionName){
                    $actionName = $actionName.'Index';
                }
                $parameters = $segments;
                $found = $this->useControllerAction($controllerName, $actionName, $parameters);
            }
        }

        // Вызов экшена 404
        if(!$found){
            $controllerName = 'Site' . 'Controller';
            $actionName = 'action404';
            $this->useControllerAction($controllerName, $actionName);
        }
    }

    /**
     * Метод для проверки существования экшена в контроллере
     */
    public function checkDefaultContoller($segment)
    {
        $controllerFile = App::$path . '/controllers/SiteController.php';
        if (file_exists($controllerFile)) {
            include_once($controllerFile);
            $controller = new \SiteController();

            $tmp_segment = explode('-', $segment);
            foreach ($tmp_segment as &$tml_s){
                $tml_s = ucfirst($tml_s);
            }
            $segment = implode("", $tmp_segment);

            $action = 'action' . $segment;
            if(method_exists($controller, $action)){
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    /**
     * Использование экшенов в контроллере
     */
    public function useControllerAction($controller, $action, $parameters = [])
    {
        $action_name = mb_strtolower(mb_substr($action, 6));
        App::$controller = mb_strtolower(mb_substr($controller, 0, -10));
        App::$action = mb_strtolower($action_name);
        $tmp_action = explode('-', $action_name);
        foreach ($tmp_action as &$tml_s){
            $tml_s = ucfirst($tml_s);
        }
        $tmp_action = implode("", $tmp_action);
        $action = 'action'.$tmp_action;

        $controllerFile = App::$path . '/controllers/' . $controller . '.php';
        if (file_exists($controllerFile)) {
            include_once($controllerFile);
            $controllerObject = new $controller;
            call_user_func_array(array($controllerObject, $action), $parameters);
            return true;
        }
        return false;
    }

    /**
     * Проверка CSRF
     */
    private function validationCSRF()
    {
        if(count($_POST) > 0 && !Csrf::checkCsrf()){
            return false;
        }
        return true;
    }

}
