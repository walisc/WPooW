<?php

namespace wpOOW;

use Composer\InstalledVersions;
use wpOOW\Core\RestApi\wpRestApi;
use wpOOW\Core\wpAPIUtilities;

use wpOOW\Core\PageTypes\PostType;
use wpOOW\Core\PageTypes\SubMenu;
use wpOOW\Core\PageTypes\Menu;
use wpOOW\Core\PageTypes\SubMenuSeparator;

use wpOOW\Utilities\VersionDetails;

use wpOOW\Core\Permissions\WP_PERMISSIONS;

/**
 * Class wpAPI
 *
 * Forms the entry point of the wpAPI wrapper. Contains main methods for creating elements
 *
 * @namespace wpAPI
 *
 */

 class Foo {}


class wpAPI
{
    const PACKAGE_NAME = "centrid/wpoow";
    /**
     * wpAPI constructor.
     *
     */
    function __construct()
    {

        define( 'URL_SEPARATOR',  '/' );

        define( 'WP_API_PATH_ABS', wpAPIUtilities::GetRealPath(dirname(defined('WP_API_CLS_CUSTOM_LOCATION') ? WP_API_CLS_CUSTOM_LOCATION : __FILE__)) . '/' );
        define( 'WP_API_PATH_REL', str_replace(ABSPATH, '',  wpAPIUtilities::GetRealPath( __DIR__ )) . '/' );
        define( 'WP_API_ELEMENT_PATH_REL', WP_API_PATH_REL . "Core" . DIRECTORY_SEPARATOR . "Elements" .DIRECTORY_SEPARATOR);

        define( 'WP_API_URI_PATH', wpAPIUtilities::GetWpAPUriLocation(dirname(defined('WP_API_CLS_CUSTOM_LOCATION') ? WP_API_CLS_CUSTOM_LOCATION : __FILE__)) . URL_SEPARATOR);
        define( 'WP_API_ELEMENT_URI_PATH', WP_API_URI_PATH  . "Core" . URL_SEPARATOR . "Elements" . URL_SEPARATOR);
        define( 'WP_API_PAGE_TYPES_URI_PATH', WP_API_URI_PATH  . "Core" . URL_SEPARATOR . "PageTypes" . URL_SEPARATOR);
     



    }
    //TODO: Id validation. Also note Id/slug cannot be to long
    /**
     * Create a new menu option that can be added to the wp-admin menu.
     *
     * @param $page_slug
     * @param $menu_title
     * @param $capability
     * @param $display_path
     * @param string $icon
     * @param null $position
     * @return Menu
     */
    public function CreateMenu($page_slug, $menu_title, $capability=WP_PERMISSIONS::MANAGE_OPTIONS, $display_path=null, $icon='', $position=null)
    {
        return new Menu($page_slug, $menu_title ,$capability,$display_path, $icon,$position);

    }

    /**
     *
     * Creates a sub menu that can be added to wpAPI wrapper Menu.
     *
     * @param $page_slug
     * @param $menu_title
     * @param $capability
     * @param $display_path
     * @return SubMenu
     */
    public function CreateSubMenu($page_slug, $menu_title, $capability, $display_path)
    {
        return new SubMenu($page_slug, $menu_title ,$capability,$display_path);

    }

    public function CreateSubMenuSeparator($seperator_slug, $seperator_title=null){
        return new SubMenuSeparator($seperator_slug, $seperator_title);
    }

    public function RegisterRestApi($api_namespace){
        return new wpRestApi($api_namespace);
    }
    /**
     *
     * Create a new post-type page with a sub menu link that can be added to the wpAPI wrapper Menu
     *
     * @param $page_slug
     * @param $title
     * @param bool $persist
     * @return PostType
     */
    public function CreatePostType($page_slug, $title, $persist=false, $options=[])
    {
        return new PostType($page_slug, $title , $persist, $options);

    }
    
    public function GetVersion()
    {
        // TODO: Might need to rethink this. Makes wpOOW dependant on composer, which is not the goal
        $composerFile = InstalledVersions::getInstallPath(wpAPI::PACKAGE_NAME) .DIRECTORY_SEPARATOR . "composer.json";
        return new VersionDetails(json_decode(file_get_contents($composerFile), true));
    }


}
