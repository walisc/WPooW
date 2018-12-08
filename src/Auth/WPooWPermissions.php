<?php

namespace WPooW\Auth;

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

class WPooWPermissions
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
        WPooWPermissions::ViewPage => "cru",
        WPooWPermissions::AddPage => "cru",
        WPooWPermissions::EditPage => "cru",
        WPooWPermissions::EditTable => "cru",
        WPooWPermissions::ViewTable => "cru",

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
    * @return WPooWPermissions
    * @throws Exception
    */
    public static function SetPermission($permissions = [])
    {
        if (!is_array($permissions)) {
            throw new Exception("Permission should be an array with the 4 view states EditTable, EditPage, ViewTable, ViewPage");
        }
        $wP = new WPooWPermissions();


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
