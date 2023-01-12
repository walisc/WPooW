<?php


/**
 * Class wpAPIUtilities
 * Utilities class for the wpOOW library
 * @package wpAPI\Core
 */
class wpAPIUtilities
{
    /**
     * @param $className
     * @param $classMethod
     * @param $params
     * @return mixed
     */
    public static function CallUserFunc($className, $classMethod, $params)
    {
        if (is_callable($classMethod))
        {
            return call_user_func_array($classMethod, $params);
        }
        return call_user_func_array([$className, $classMethod], $params);

    }

    /**
     * Gets the real path for a phar file
     * @param $path
     * @return mixed
     */
    public static function GetRealPath($path)
    {
        return str_replace("phar://", "", $path);
    }

    /**
     * Get the the uri location of the wpOOW project
     * @param $wpAPIPath
     * @return string
     */
    public static function GetWpAPUriLocation($wpAPIPath)
    {
        $templateDirectory = explode('/', str_replace("\\", "/", get_template_directory())); //Bug with wordpress. Doesn't create the correct URL when on windows. See https://developer.wordpress.org/reference/functions/get_template_directory/

        // Taking acccount of phar files
        $wpAPIPath = str_replace("phar://","", str_replace("\\", "/", $wpAPIPath));
        $wpAPIPathArr = explode('/', $wpAPIPath);

        foreach ($templateDirectory as $templatePathItem)
        {
            if ($templatePathItem == "wp-content") #TODO look for wp-content constant
            {
                return get_site_url() . URL_SEPARATOR. implode(URL_SEPARATOR, $wpAPIPathArr);
            }
            foreach ($wpAPIPathArr as $key => $wpAPIPathItem)
            {
                if ($wpAPIPathItem == $templatePathItem)
                {
                    unset($wpAPIPathArr[$key]);
                    break;
                }
            }
        }
    }
}