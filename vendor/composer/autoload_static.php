<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbe584f22f4841cd5a5684daebbdd28db
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbe584f22f4841cd5a5684daebbdd28db::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbe584f22f4841cd5a5684daebbdd28db::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}