<?php

namespace WPooW;
include_once __DIR__.'/../vendor/autoload.php';

use WPooW\APIs\PageTypesApi;
use WPooW\APIs\MenusApi;

use WPooW\Core\Elements\Autoloader;
use WPooW\Utilities\VersionDetails;
use WPooW\Utilities\Utilities;


use WPooW\Core\PageTypes\PostType;
use WPooW\Core\PageTypes\SubMenu;
use WPooW\Core\PageTypes\Menu;

use WPooW\Auth\WPPermissions;

/**
 * Class wpAPI
 *
 * Forms the entry point of the wpAPI wrapper. Contains main methods for creating elements
 *
 * @namespace wpAPI
 *
 */
class WPooW
{ 
    /**
     * wpAPI constructor.
     *
     */

    public $PageTypes;
    public $Menus;

    function __construct()
    {

        Autoloader::register();
        
        define( 'URL_SEPARATOR',  '/' );

        define( 'WP_API_PATH_ABS', Utilities::GetRealPath(dirname(__FILE__)) . '/' );
        define( 'WP_API_PATH_REL', str_replace(ABSPATH, '',  Utilities::GetRealPath( __DIR__ )) . '/' );
        define( 'WP_API_ELEMENT_PATH_REL', WP_API_PATH_REL . "Core" . DIRECTORY_SEPARATOR . "Elements" .DIRECTORY_SEPARATOR);

        define( 'WP_API_URI_PATH', Utilities::GetWpAPUriLocation(dirname(__FILE__)) . URL_SEPARATOR);
        define( 'WP_API_ELEMENT_URI_PATH', WP_API_URI_PATH  . "Core" . URL_SEPARATOR . "Elements" . URL_SEPARATOR);


        $this->PageTypes = new PageTypesApi();
        $this->Menus = new MenusApi();



    }

    public function GetVersion()
    {
        $composerFile = dirname(__FILE__) .DIRECTORY_SEPARATOR . "../composer.json";
        return new VersionDetails(json_decode(file_get_contents($composerFile), true));
    }

    /**
     * @deprecated
     */
    public function CreateMenu($page_slug, $menu_title, $capability=WPPermissions::MANAGE_OPTIONS, $display_path=null, $icon='', $position=null)
    {
        trigger_error('Method ' . __METHOD__ . ' is deprecated. Please use $WPooW->Menus->CreateMenu', E_USER_DEPRECATED);
        return new Menu($page_slug, $menu_title ,$capability,$display_path, $icon,$position);

    }

    /**
     * @deprecated
     */
    public function CreateSubMenu($page_slug, $menu_title, $capability, $display_path)
    {
        trigger_error('Method ' . __METHOD__ . ' is deprecated. Please use $WPooW->Menus->CreateSubMenu', E_USER_DEPRECATED);
        return new SubMenu($page_slug, $menu_title ,$capability,$display_path);

    }

    /**
     * @deprecated
     */
    public function CreatePostType($page_slug, $title, $persist=false, $options=[])
    {
        trigger_error('Method ' . __METHOD__ . ' is deprecated. Please use $WPooW->Pages->CreatePostType', E_USER_DEPRECATED);
        return new PostType($page_slug, $title , $persist, $options);

    }

}










