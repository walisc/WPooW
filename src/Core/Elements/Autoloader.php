<?php

/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/08/13
 * Time: 8:35 PM
 */
class Elements_Autoloader
{
    private $baseDir;


    public function __construct($baseDir = null)
    {
        if ($baseDir === null) {
            $baseDir = dirname(__FILE__);
        }

        $realDir = realpath($baseDir);
        if (is_dir($realDir)) {
            $this->baseDir = $realDir;
        } else {
            $this->baseDir = $baseDir;
        }
    }

    public static function register($baseDir = null)
    {
        $loader = new self($baseDir);
        spl_autoload_register(array($loader, 'autoload'));

        return $loader;
    }


    public function autoload($class)
    {
        if ($class[0] === '\\') {
            $class = substr($class, 1);
        }


        $file = sprintf('%s/%s/%s.php', $this->baseDir, str_replace('_', '/', $class), str_replace('_', '/', $class));
        if (is_file($file)) {
            require $file;
        }
    }
}