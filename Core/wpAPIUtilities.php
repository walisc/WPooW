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

    public static function GetWpAPUriLocation($wpAPIPath)
    {
        $templateDirectory = explode(DIRECTORY_SEPARATOR, get_template_directory());
        $wpAPIPath = explode(DIRECTORY_SEPARATOR, $wpAPIPath);

        foreach ($templateDirectory as $templatePathItem)
        {
            if ($templatePathItem == "wp-content") #TODO look for wp-content constant
            {
                return get_site_url() . URL_SEPARATOR. implode(URL_SEPARATOR, $wpAPIPath);
            }
            foreach ($wpAPIPath as $key => $wpAPIPathItem)
            {
                if ($wpAPIPathItem == $templatePathItem)
                {
                    unset($wpAPIPath[$key]);
                    break;
                }
            }
        }
    }
}