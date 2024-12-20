<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfb8accf56971be5360d075154783ffa8
{
    public static $prefixLengthsPsr4 = array (
        'Q' => 
        array (
            'Quixotix\\' => 9,
            'Quixotify\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Quixotix\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Quixotix',
        ),
        'Quixotify\\' => 
        array (
            0 => __DIR__ . '/..' . '/justinlawrencems/quixotify/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Quixotify\\Controller' => __DIR__ . '/..' . '/justinlawrencems/quixotify/src/Controller.php',
        'Quixotify\\Generator' => __DIR__ . '/..' . '/justinlawrencems/quixotify/src/Generator.php',
        'Quixotix\\Controllers\\FakedDataController' => __DIR__ . '/../..' . '/src/Quixotix/Controllers/FakedDataController.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitfb8accf56971be5360d075154783ffa8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitfb8accf56971be5360d075154783ffa8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitfb8accf56971be5360d075154783ffa8::$classMap;

        }, null, ClassLoader::class);
    }
}
