<?php

/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/09/06
 * Time: 7:35 PM
 */
class wpAPIUtilities
{
    public static function CallUserFunc($className, $classMethod, $params)
    {
        if (is_callable($classMethod))
        {
            return call_user_func_array($classMethod, $params);
        }
        return call_user_func_array([$className, $classMethod], $params);

    }

    public static function GetRealPath($path)
    {
        return str_replace("phar://", "", $path);
    }
}