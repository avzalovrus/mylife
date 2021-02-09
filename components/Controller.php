<?php

namespace Component;


/**
 * Класс Controller
 * Компонент приложения
 */
abstract class Controller
{
    protected $app;
    protected $header = null;
    protected $footer = null;

    function __construct()
    {
        $this->app = App::$path;
        if(file_exists($this->app.'/views/layouts/header.php')){
            $this->header = 'header';
        }
        if(file_exists($this->app.'/views/layouts/footer.php')){
            $this->footer = 'footer';
        }
    }

    /**
     * render генерация html для этого контроллера
     */
    protected function render($view, $parameters = []){
        if(!$this->checkBehavior()){
            $app = array_search($this->app, App::$apps);
            header('Location: /'.$app);
        }

        extract($parameters, EXTR_SKIP);

        $_SERVER["DOCUMENT_ROOT"] = App::$root . '/assets';
        if($this->header && file_exists($this->app.'/views/layouts/'.$this->header.'.php')){
            require_once $this->app.'/views/layouts/'.$this->header.'.php';
        }
        require_once $this->app.'/views/'.App::$controller.'/'.$view.'.php';

        echo Csrf::registerCsrf();

        if($this->footer && file_exists($this->app.'/views/layouts/'.$this->footer.'.php')){
            require_once $this->app.'/views/layouts/'.$this->footer.'.php';
        }
    }

    /**
     * render генерация html для любого контроллера
     */
    protected function redirect($view, $parameters = []){
        exit();
        extract($parameters, EXTR_SKIP);
        require_once $this->app.'/views/'.$view.'.php';
    }

    /**
     * render генерация html для любого контроллера
     */
    protected function checkBehavior(){
        if(method_exists($this, 'behaviors')){
            $behaviors = $this->behaviors();
            $status = App::$user->status;
            if(!isset($behaviors['access'][$status])) {
                throw new \Exception("Для статуса пользователя не определено поведение.");
            }
            if(in_array(App::$action, $behaviors['access'][$status])){
                return true;
            }
            return false;
        }
        return true;
    }


}

?>