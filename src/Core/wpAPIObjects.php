<?php


/**
 * Class wpAPIObjects
 * Global Cache of the wpOOW objects. Allows for them to be used in rendering views
 * @package wpAPI\Core
 */
class wpAPIObjects
{

    private $wpapi_objects = [];

    #TODO: Consider removing the method completely...add object directly
    /**
     * @return bool|mixed
     */
    public static function GetInstance()
    {

        wp_cache_add('wpAPIObjects', new wpAPIObjects());
        return wp_cache_get('wpAPIObjects');
    }

    /**
     * @param $key
     * @param $object
     */
    public function AddObject($key, $object)
    {
        //TODO: check if key already exists

        $this->wpapi_objects[$key] = $object;
        wp_cache_set('wpAPIObjects', $this);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function GetObject($key)
    {
        return $this->wpapi_objects[sanitize_title_with_dashes($key)];
    }

    /**
     * @param $key
     */
    public function RemoveObject($key)
    {

    }

}