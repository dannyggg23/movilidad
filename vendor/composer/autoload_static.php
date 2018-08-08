<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit782b264af769baf39618cc876eaad6c0
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Tavo\\' => 5,
        ),
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Tavo\\' => 
        array (
            0 => __DIR__ . '/..' . '/tavo1987/ec-validador-cedula-ruc/src',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit782b264af769baf39618cc876eaad6c0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit782b264af769baf39618cc876eaad6c0::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
