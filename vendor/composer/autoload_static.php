<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit946f299bdc4713e480b3376f30405e3e
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit946f299bdc4713e480b3376f30405e3e::$classMap;

        }, null, ClassLoader::class);
    }
}