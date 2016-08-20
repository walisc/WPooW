<?php

/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/07/04
 * Time: 11:54 PM
 */
class wpAPIObjects
{

    private static $instance;
    private $wpapi_objects = [];

    public static function GetInstance()
    {
        if (wpAPIObjects::$instance === null)
        {
            wpAPIObjects::$instance = new wpAPIObjects();
        }
        return wpAPIObjects::$instance;
    }

    public function AddObject($key, $object)
    {
        //TODO: check if key already exists
        $this->wpapi_objects[$key] = $object;
    }

    public function GetObject($key)
    {
        return $this->wpapi_objects[$key];
    }

    public function RemoveObject($key)
    {

    }

}