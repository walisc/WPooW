<?php

/**
 * Class wpAPIObjects
 *
 * Class wpAPIObjects. Keeps AwpAPI objects created during initialization which can then be used later on on, for instance when rendering a page
 *
 * @package wpAPI\Core
 *
 */
 class wpAPIObjects
{

    private $wpapi_objects = [];

    #TODO: Consider removing the method completely...add object directly
    public static function GetInstance()
    {

        wp_cache_add('wpAPIObjects', new wpAPIObjects());
        return wp_cache_get('wpAPIObjects');
    }

    public function AddObject($key, $object)
    {
        //TODO: check if key already exists

        $this->wpapi_objects[$key] = $object;
        wp_cache_set('wpAPIObjects', $this);
    }

    public function GetObject($key)
    {
        return $this->wpapi_objects[$key];
    }

    public function RemoveObject($key)
    {

    }

}