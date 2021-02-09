<?php

namespace Component;

/**
 * Класс User
 * Компонент приложения для управления пользователем
 */
class User
{
    public $id = null;
    public $status = null;
    public $name = null;

    function __construct()
    {
        $this->checkAuth();
    }

    /**
     * @return bool
     * Метод авторизации
     */
    public function login()
    {
        if('1naesis' === $_POST['login'] && '111111' === $_POST['pass']){
            $_SESSION['user_auth']['id'] = 1;
            $_SESSION['user_auth']['name'] = '1naesis';
            $_SESSION['user_auth']['status'] = 1;
            $this->checkAuth();
            return true;
        }
        return false;
    }

    /**
     *  Метод проверки авторизации
     */
    public function logout()
    {
        unset($_SESSION['user_auth']);
        $this->checkAuth();
        return true;
    }

    /**
     *  Метод проверки авторизации
     */
    private function checkAuth()
    {
        $this->id = $_SESSION['user_auth']['id']??0;
        $this->status = $_SESSION['user_auth']['status']??0;
        $this->name = $_SESSION['user_auth']['name']??'Гость';
        return true;
    }
}

?>