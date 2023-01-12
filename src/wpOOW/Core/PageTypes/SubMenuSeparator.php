<?php

namespace wpOOW\Core\PageTypes;


/**
 * Class SubMenuSeparator
 * Custom page which uses the wpAPI_VIEW to render a page
 * This page appears as a Submenu in the wordpress backend and added to the Menu Page
 * 
 * @package wpAPI\Core\PageType
 */
class SubMenuSeparator extends SubMenu
{
    protected $seperator_title = null;

    public function __construct($seperator_slug, $seperator_title=null)
    {
        $this->slug = $seperator_slug;
        $this->seperator_title = $seperator_title;
    }

    public function Render($parent_slug=null)
    {
        parent::Render($parent_slug);
    }

    public function Generate()
    {
        $this->AddSubMenuSeparator();
    }

    protected function AddSubMenuSeparator( ){
            global $submenu, $menu, $_wp_real_parent_file;
        
            $parent_slug = $this->parent_slug;
        
            if ( isset( $_wp_real_parent_file[ $parent_slug ] ) ) {
                $parent_slug = $_wp_real_parent_file[ $parent_slug ];
            }
        
        
            /*
             * If the parent doesn't already have a submenu, add a link to the parent
             * as the first item in the submenu. If the submenu file is the same as the
             * parent file someone is trying to link back to the parent manually. In
             * this case, don't automatically add a link back to avoid duplication.
             */
            if ( ! isset( $submenu[ $parent_slug ] )) {
                foreach ( (array) $menu as $parent_menu ) {
                    if ( $parent_menu[2] === $parent_slug && current_user_can( $parent_menu[1] ) ) {
                        $submenu[ $parent_slug ][] = array_slice( $parent_menu, 0, 4 );
                    }
                }
            }
        
            if ($this->seperator_title){
                $new_sub_menu =  array($this->seperator_title,'read',$this->slug,'','wpoow-menu-separator');
            }
            else{
                $new_sub_menu =  array('','read',$this->slug,'','wpoow-menu-separator-blank');
            }

            $submenu[ $parent_slug ][] = $new_sub_menu;
            
        
        }
        
    }
