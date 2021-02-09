<?php

namespace Component;


/**
 * Класс Csrf
 * Компонент приложения
 */
abstract class Csrf
{
    private static $csrf_name;
    private static $csrf_param;

    public static function registerCsrf()
    {
        if(!self::$csrf_name || !self::$csrf_param){
            self::setCsrf();
        }
        $_SESSION[self::$csrf_name] = self::$csrf_param;

        return '<input type="hidden" name="csrf-name" value="'. self::$csrf_name .'">
                <input type="hidden" name="csrf-param" value="'. self::$csrf_param .'">';
    }

    public static function getCsrf()
    {
        if(!self::$csrf_name || !self::$csrf_param){
            self::setCsrf();
        }
        return '<input type="hidden" name="'. self::$csrf_name .'" value="'. self::$csrf_param .'">';
    }

    public static function checkCsrf()
    {
        if(isset($_POST["asm-csrf-sec"]) && isset($_SESSION["asm-csrf-sec"]) && $_POST["asm-csrf-sec"] === $_SESSION["asm-csrf-sec"]){
            return true;
        }
        if(in_array(App::$url, App::$config['csrf-whitelist'])){
            return true;
        }
        return false;
    }

    public static function setCsrf()
    {
        self::$csrf_name = "asm-csrf-sec";
        self::$csrf_param = md5(time());
    }
}