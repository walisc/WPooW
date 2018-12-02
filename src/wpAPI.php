<?php

//TODO: Use autoloading
include_once 'Core/wpAPIUtilities.php';
include_once 'Core/wpAPIBasePage.php';
include_once 'Core/wpAPIObjects.php';
include_once 'Core/wpQueryObject.php';
include_once 'Core/Elements/BaseElement.php';

include_once 'Core/PageTypes/PostType.php';
include_once 'Core/PageTypes/SubMenu.php';
include_once 'Core/PageTypes/Menu.php';

include_once 'Libraries/twig/twig/lib/Twig/Autoloader.php';
include_once 'Core/Elements/Autoloader.php';
include_once 'Utilities/versionDetails.php';

/**
 * Class wpAPI
 *
 * Forms the entry point of the wpAPI wrapper. Contains main methods for creating elements
 *
 * @namespace wpAPI
 *
 */
class wpAPI
{
    /**
     * wpAPI constructor.
     *
     */
    function __construct()
    {
        //TODO: Add helper
        Twig_Autoloader::register();
        Elements_Autoloader::register();

        define( 'URL_SEPARATOR',  '/' );

        define( 'WP_API_PATH_ABS', wpAPIUtilities::GetRealPath(dirname(__FILE__)) . '/' );
        define( 'WP_API_PATH_REL', str_replace(ABSPATH, '',  wpAPIUtilities::GetRealPath( __DIR__ )) . '/' );
        define( 'WP_API_ELEMENT_PATH_REL', WP_API_PATH_REL . "Core" . DIRECTORY_SEPARATOR . "Elements" .DIRECTORY_SEPARATOR);

        define( 'WP_API_URI_PATH', wpAPIUtilities::GetWpAPUriLocation(dirname(__FILE__)) . URL_SEPARATOR);
        define( 'WP_API_ELEMENT_URI_PATH', WP_API_URI_PATH  . "Core" . URL_SEPARATOR . "Elements" . URL_SEPARATOR);



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
        $composerFile = dirname(__FILE__) .DIRECTORY_SEPARATOR . "composer.json";
        return new VersionDetails(json_decode(file_get_contents($composerFile), true));
    }


}

/**
 *
 * Class responsible for rendering wp-admin menu pages. This uses the php template engine twig.
 * A wpAPI_VIEW can either be link to a twig template or be based on a template string
 *
 * Class wpAPI_VIEW
 * @package wpAPI\Base
 */
class wpAPI_VIEW
{
    /**
     * Constant that lets wpAPI_VIew know that it should render the page based on a twig template file
     */
    CONST PATH = 1;
    /**
     * Constant that lets wpAPI_VIew know that it should render the page based on a template string
     */
    CONST CONTENT = 2;

    /**
     * Either wpAPI_VIEW::PATH or wpAPI_VIEW::CONTENT
     * @var
     */
    private $type;

    /**
     * When wpAPI_VIEW::PATH is used for type this links to the path of the twig template
     * When wpAPI_VIEW::CONTENT is used for type this is used as a string template for rendering the page
     * @var
     */

    private $path_content;

    /**
     *
     * key, value array to use in the twig template/string
     *
     * @var array
     */
    private $data = [];

    /**
     * wpAPI_VIEW constructor.
     * @param $type
     * @param $path_content
     * @param $data
     */
    function __construct($type, $path_content, $data=[])
    {
        $this->type = $type;
        $this->path_content = $path_content;
        $this->data = array_merge($this->data, $data);

    }

    /**
     *
     * Method that renders the twig template
     *
     */
    function Render()
    {
        
        if ($this->type == self::PATH)
        {
            //TODO: Make this global

            $loader = new Twig_Loader_Filesystem(ABSPATH);
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

    /**
     *
     * Allows you to set the key, value data to be used when rendering the view.
     * The data can either be appended to already existing data, or replace already existing data.
     *
     * @param $data
     * @param bool $append
     */
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

/**
 * Class WP_PERMISSIONS
 *
 * wrapper for the main wordpress permissions
 * @package wpAPI\Base
 */
class WP_PERMISSIONS
{
    
    CONST MANAGE_OPTIONS = "manage_options";
}


/**
 * Class wpAPIPermissions
 *
 * Permission class used for the wpAPI_VIEW.
 * wpAPI Elements can set of permissions which will be linked to the wpAPI_VIEW permissions (viewstate) for a page.
 * Based on this, elements can either have create, read, update delete rights.
 *
 * For the wpAPI there are 5 viewstates that a wpAPI page can have. The view of a page can set using by setting the
 * global $CURRENT_VIEW_STATE when the page is created. By default this permission/viewsate is @EditPage. These
 * viewstates are detailed below. It must be noted that these can be used arbitrarily. The names are more suggestive
 * as to when to use them
 *
 *      ViewPage - Can be use for a custom page when it is in read only mode. Not always usually useful for wordpress
 *                  as most pages are not readonly
 *      AddPage - Can be used on a custom page when adding elements. In this case, not always useful in wordpress.
 *               - When you use the wpAPI post type this is set when you add a new post
 *      EditPage - This is default view state set for all pages. Can be used on a custom page when editing elements.
 *                - When you use the wpAPI post type this is set when you editing a new post
 *      ViewTable - Can be used on a custom page when you have a table/grid layout
 *                 - When you use the wpAPI post type this is set when you are view all your post types
 *      EditTable - Can be used on a custom page when you have a edited inline table/grid layout
 *                 - TODO: enable on wordpress inline editing
 *
 * For each of these viewstate and element can either create, read and update permissions set. By default all viewstates
 * for an element are set to create, read, update. i.e
 *
 *  element.WP_PERMISSIONS  = [
                                wpAPIPermissions::ViewPage => "cru",
                                wpAPIPermissions::AddPage => "cru",
                                wpAPIPermissions::EditPage => "cru",
                                wpAPIPermissions::EditTable => "cru",
                                wpAPIPermissions::ViewTable => "cru",

                              ];
 *
 * when a page is rendered the permission of each wpAPI element is is computed dependant on the page viewstate. The
 * element is
 * - presented if read permissions are true.
 * - editable if update permissions are true and the viewstate matches.
 * - editable if create permissions are true and the viewstate matches.
 *
 * @package wpAPI\Base
 */


class wpAPIPermissions
{

    const ViewPage = "ViewPage";
    const AddPage = "AddPage";
    const EditPage = "EditPage";
    const ViewTable = "ViewTable";
    const EditTable = "EditTable";

    // cru - create - read - update
    /**
     * @var array
     */
    private $permissionMatrix = [
        wpAPIPermissions::ViewPage => "cru",
        wpAPIPermissions::AddPage => "cru",
        wpAPIPermissions::EditPage => "cru",
        wpAPIPermissions::EditTable => "cru",
        wpAPIPermissions::ViewTable => "cru",

    ];

    /**
    * An array with viewstate -> permission  relations to be set for the element such as the field. Example Below
    *
    *       [
    *           "EditTable" = > "cr"
    *           "EditPage" = > "cru"
    *           "ViewTable" = > "cru"
    *           "ViewPage" = > "cru"
    *       ]
     *
    * Note:- for viewstates you can use the const i.e wpAPIPermissions::EditTable => "cru"
    * Also note by default all viewstates have the permission cru.
    * because of this you don't have to specify all viewstates. Only the one you want
    *
    *
    * @param array $permissions
    * @return wpAPIPermissions
    * @throws Exception
    */
    public static function SetPermission($permissions = [])
    {
        if (!is_array($permissions)) {
            throw new Exception("Permission should be an array with the 4 view states EditTable, EditPage, ViewTable, ViewPage");
        }
        $wP = new wpAPIPermissions();


        foreach ($permissions as $pageState => $permission) {
            $wP->permissionMatrix[$pageState] = $permission;
        }
        return $wP;
    }


    /**
     * Get the permission of a pagestate
     *
     * @param $pageState
     * @return mixed
     */
    public function GetPermission($pageState)
    {
        //TODO: consider returning an object
        return $this->permissionMatrix[$pageState];
    }

    //TODO: Rename this to check permission
    /**
     * See is for the given page state an action is allow. Action can either be u(pdate), or r(ead), v(iew)
     *
     * @param $pageState
     * @param $action
     * @return bool|int
     */
    public function CheckPermissionAction($pageState, $action)
    {
        return strpos($this->permissionMatrix[$pageState], $action);
    }

    public function CanEdit($pageState)
    {
        return strpos($this->permissionMatrix[$pageState], 'u');
    }

    public function CanRead($pageState)
    {
        return strpos($this->permissionMatrix[$pageState], 'r');
    }

    public function CanCreate($pageState)
    {
        return strpos($this->permissionMatrix[$pageState], 'c');
    }


}

class_alias('wpAPIPermissions', 'wpPer');

