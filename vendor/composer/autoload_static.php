<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd9c439a4cdba0b68fc4420f7785ded18
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'Rafa\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Rafa\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/RafaelCapoani',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd9c439a4cdba0b68fc4420f7785ded18::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd9c439a4cdba0b68fc4420f7785ded18::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
