<?php

/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/05/30
 * Time: 9:31 PM
 */
//TODO: Use autoloading
include_once  'Core/wpAPIUtilities.php';
include_once 'Core/wpAPIBasePage.php';
include_once 'Core/wpAPIObjects.php';
include_once 'Core/Elements/BaseElement.php';

include_once 'Core/PageTypes/PostType.php';
include_once 'Core/PageTypes/SubMenu.php';
include_once 'Core/PageTypes/Menu.php';

include_once 'Libraries/twig/twig/lib/Twig/Autoloader.php';
include_once 'Core/Elements/Autoloader.php';

class wpAPI
{
    function __construct()
    {
        //TODO: Add helper
        Twig_Autoloader::register();
        Elements_Autoloader::register();

        define( 'WP_API_PATH_ABS', dirname(__FILE__) . '/' );
        define( 'WP_API_PATH_REL', str_replace(ABSPATH, '', __DIR__) . '/' );
        define( 'WP_API_ELEMENT_PATH_REL', WP_API_PATH_REL . "Core" . DIRECTORY_SEPARATOR . "Elements" .DIRECTORY_SEPARATOR);


    }
    //TODO: Id validation. Also note Id/slug cannot be to long
    public function CreateMenu($page_slug, $menu_title, $capability, $display_path, $icon='', $position=null)
    {
        return new Menu($page_slug, $menu_title ,$capability,$display_path, $icon,$position);

    }

    public function CreateSubMenu($page_slug, $menu_title, $capability, $display_path)
    {
        return new SubMenu($page_slug, $menu_title ,$capability,$display_path);

    }

    public function CreatePostType($page_slug, $title)
    {
        return new PostType($page_slug, $title );

    }


}

class wpAPI_VIEW
{
    CONST PATH = 1;
    CONST CONTENT = 2;

    private $type;
    private $path_content;
    private $data = [];

    function __construct($type, $path_content, $data)
    {
        $this->type = $type;
        $this->path_content = $path_content;
        $this->data = array_merge($this->data, $data);

    }
    
    function Render()
    {
        
        if ($this->type == self::PATH)
        {
            //TODO: Make this global

            $loader = new Twig_Loader_Filesystem(__DIR__.'/../../');
            $twig = new Twig_Environment($loader);

            echo $twig->render($this->path_content, $this->data);
        }
        else if ($this->type == self::CONTENT)
        {

            $loader = new Twig_Loader_Array(array(
                'page.html' => $this->path_content,
            ));

            $twig = new Twig_Environment($loader);

            echo $twig->render('page.html', $this->data);

        }



    }
    
    function SetData($data, $append=true)
    {
        if ($append) {
            $this->data = array_merge($this->data, $data);
        }
        else
        {
            $this->data = $data;
        }

    }
}

class wpAPI_PERMISSIONS
{
   CONST MANAGE_OPTIONS = "manage_options";
}


