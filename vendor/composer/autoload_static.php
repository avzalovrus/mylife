<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit10f92951a2618ce29fa0ac29a1a31289
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'RedBeanPHP\\' => 11,
        ),
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'C' => 
        array (
            'Component\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'RedBeanPHP\\' => 
        array (
            0 => __DIR__ . '/..' . '/gabordemooij/redbean/RedBeanPHP',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Component\\' => 
        array (
            0 => __DIR__ . '/../..' . '/components',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit10f92951a2618ce29fa0ac29a1a31289::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit10f92951a2618ce29fa0ac29a1a31289::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
