<?php

namespace Component;

use Component\Router;
use Component\User;

/**
 * Класс App
 * Компонент приложения
 */
class App
{
    static public $url = null;
    static public $root = null;
    static public $path = null;
    static public $apps = null;
    static public $user = null;
    static public $get = null;
    static public $post = null;
    static public $files = null;
    static public $config = null;
    static public $action = null;
    static public $controller = null;

    /**
     * Запуск приложения
     */
    public static function run()
    {
        self::includeConfig();
        self::includePath();
        require_once self::$path . '/index.php';
        self::$user = new User();
        $router = new Router();
        $router->run();
    }

    /**
     *  Поиск и подключение приложений, подключение моделей из приложений
     */
    public static function includePath()
    {
        if(!file_exists(ROOT . '/config/apps.php')){
            throw new \Exception("Ошибка: Отсутствует файл apps.php, по пути '". ROOT . "/config/'");
        }
        $apps = require_once ROOT . '/config/apps.php';
        if(!isset($apps['']))throw new \Exception("Приложение по умолчанию не определено.");

        self::$path = ROOT.'/'.$apps[''];
        self::$root = '/'.$apps[''];
        foreach ($apps as $app_find => $path_find){
            if(file_exists(ROOT.'/'.$path_find)){
                self::$apps[$app_find] = ROOT.'/'.$path_find;
                if(explode('/', $_SERVER["REQUEST_URI"])[1] === $app_find){
                    self::$path = ROOT.'/'.$path_find;
                    self::$root = '/'.$path_find;
                }

                //Подключение всех моделей
                if(file_exists($path_find . '/models/')) {
                    foreach (scandir($path_find . '/models/') as $model) {
                        if (2 < strlen($model) && file_exists(ROOT . '/' . $path_find . '/models/' . $model)) {
                            require_once ROOT . '/' . $path_find . '/models/' . $model;
                        }
                    }
                }
            }
        }
    }

    /**
     *  Поиск и подключение приложений, подключение моделей из приложений
     */
    public static function includeConfig()
    {
        if(!file_exists(ROOT.'/config/config.php')) {
            throw new Exception("Ошибка: Отсутствует файл config.php, по пути '". ROOT . "/config/'");
        }
        self::$config = require_once ROOT.'/config/config.php';
    }
}

?>