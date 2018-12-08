<?php

namespace WPooW\APIs;

use WPooW\Utilities\CONSTS;
use WPooW\Core\Menus\BaseMenu;
use WPooW\Core\Menus\SubMenu;


class MenusApi{

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
    public function CreateMenu($page_slug, $menu_title, $capability=CONSTS::WP_AUTH_MANAGE_OPTIONS, $display_path=null, $icon='', $position=null)
    {
        return new BaseMenu($page_slug, $menu_title ,$capability,$display_path, $icon,$position);

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
}