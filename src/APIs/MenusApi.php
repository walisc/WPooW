<?php

namespace WPooW\APIs;

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
}