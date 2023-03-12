<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita55a4a8c035901c9c94a9fffecf4b119
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/App',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita55a4a8c035901c9c94a9fffecf4b119::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita55a4a8c035901c9c94a9fffecf4b119::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita55a4a8c035901c9c94a9fffecf4b119::$classMap;

        }, null, ClassLoader::class);
    }
}
