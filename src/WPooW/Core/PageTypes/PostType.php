<?php


/**
 * Class PostType
 * Class responsible for creating the Custom post types that can be used for config
 * This will appears as a menuitem  in wordpress
 * 
 * @package wpAPI\Core\PageType
 */
class PostType extends wpAPIBasePage
{
    protected $props = [
        "labels" => [],
        "show_ui" => true,
        "show_in_menu" => true,
        "capability_type" => "post",
        "hierarchical" => false,
        "menu_position" => 30,
        "supports" => ["title"],
        "menu_icon" => null
    ];

    protected $fields = [];

    private $BeforeSaveEvents = [];
    private $BeforeDataFetch = [];
    private $AfterSaveEvents = [];
    private $onEditEvents = [];
    private $onViewEvents = [];

    private $persist = false;

    /**
     * @param $slug - the id you want to use for the posttype. Musct be unique
     * @param $label - the label to use for the menu item
     * @param bool $persist - If you want to be available when rendering pages
     * @param array $options - extend the wordpress onptions for custom posttypes
     */
    function __construct($slug, $label, $persist=false, $options = [])
    {
        parent::__construct($slug, $label);


        if (is_array($label))
        {
            $this->props["labels"] = $label;
        }
        else
        {
            $this->props["labels"] = ["name" => $label];
        }

        foreach ($options as $opt => $val){
            if (array_key_exists($opt, $this->props)){
                $this->props[$opt] = $val;
            }
        }
        $this->CreateProperties();
        $this->LoadViewState();

        $this->persist = $persist;

    }

    /**
     *
     */
    private function CreateProperties()
    {
        foreach ($this->props as $opt => $val){
            $this->{$opt} = $val;
        }
    }

    /**
     * Get the query object that can be used to obtain data for this posttype
     *
     * @return wpQueryObject
     */
    function Query()
    {
        return new wpQueryObject($this);
    }

    /**
     * Overrides the wpAPIBasePage option. Responsible for creating the custom posttype page to use
     * Called when the specified render action is called (See the RenderHook method)
     */
    function Generate()
    {
        $postTypeArray = [];

        foreach ($this->props as $opt => $val){
            $postTypeArray[$opt] = $this->{$opt};
        }

        register_post_type($this->slug, $postTypeArray);

        add_filter(sprintf("manage_%s_posts_columns", $this->slug), [$this, "SetFields"]);
        add_action(sprintf("manage_%s_posts_custom_column", $this->slug), [$this, "ViewFields"], 10, 2);
        add_action("add_meta_boxes", [$this, "EditFields"] );
        add_action('quick_edit_custom_box', [$this, "QuickEditFields"], 10, 2);
        add_action('save_post', [$this, "SaveFields"]);



        if ($this->persist)
        {
            WPAPIObjects::GetInstance()->AddObject($this->slug, $this);
        }

    }

    /**
     * Method called before data is fetched for the custom post type
     * @param $query
     */
    function BeforeDataFetch($query){

        if (array_key_exists("post_type",$query->query) && $query->query["post_type"]== $this->GetSlug())
        {
            foreach ($this->BeforeDataFetch as $observor) {
                call_user_func_array($observor, [$query]);
            }
        }


    }

    /**
     * Method used to update the viewState. Used in conjunction with wpPermission, to determine rights
     * @param null $screen
     */
    function LoadViewState($screen=null)
    {
        $action = '';

        //TODO: A bit hacky, look for ways to improve this
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        }

        if (function_exists('get_current_screen'))
        {
            if (isset(get_current_screen()->action) && get_current_screen()->action !=null)
            {
                $action = get_current_screen()->action;
            }
        }


        if ($action == "add"){
            $this->SetViewState(wpAPIPermissions::AddPage);
        }
        elseif ($action== "edit"){

            $this->SetViewState(wpAPIPermissions::EditPage);
        }
        else{
            $this->SetViewState(wpAPIPermissions::ViewTable);
        }


    }

    /**
     * The wordpress action the link the rendering to. Defaults to the init action
     * @return string
     */
    function RenderHook()
    {
        return 'init';
    }

    /**
     * Sets extra properties and then calls the Generate method
     * @param null $parent_slug
     */
    function Render($parent_slug=null)
    {
        if ($parent_slug != null)
        {
            $this->show_in_menu = $this->props["show_in_menu"] ? $parent_slug : "";
        }
        parent::Render($parent_slug);
    }

    /**
     * Returns the list of fields in a post type
     * @return array
     */
    public function GetFields()
    {
        return $this->fields;
    }

    /**
     * Returns the Db Key value for a field. This is usually a bit different to the one we set
     * @param $field_id
     * @return mixed
     */
    public function GetFieldDbKey($field_id)
    {

        return $this->fields[sprintf("%s_%s", $this->slug, $field_id)]->valueKey;
    }

    /**
     * Add a field to the posttype. These fields need to be of type BaseElement
     * @param $aField
     */
    public function AddField($aField)
    {

        if ($aField->permissions->CanRead($this->GetViewState()) !== false) {


            $aField->parent_slug = $this->slug;
            $aField->id = sprintf("%s_%s", $this->slug, $aField->id);
            $aField->saveFunction = sprintf("save_data_%s", $aField->id);
            $aField->saveNonce = sprintf("%s_meta_box_nonce", $aField->id);
            $aField->valueKey = sprintf("%s_value_key", $aField->id);

            $this->fields[$aField->id] = $aField;
        }
    }

    # Function that sets the columns for the post types
    /**
     * Method called for wordpress manage%s_post_columns hook
     * @param $fields
     * @return array
     */
    function SetFields($fields)
    {

        $postTypeFields = [];


        foreach ($this->fields as $fi)
        {
            if ($fi->permissions->CanRead($this->GetViewState())  !== false) {
                $postTypeFields[$fi->id] = $fi->label;
            }
        }

        return $postTypeFields;
    }

    # Rendering the declared columns. This is equivalent to the Grid View.
    /**
     * Iterating through the available fields and getting the read view version
     * @param $field
     * @param $post_id
     */
    function ViewFields($field, $post_id)
    {
        foreach ($this->fields as $fi)
        {
            if ($fi->id == $field)
            {
                $fi->ReadView($post_id);
                break;
            }
        }
    }

    //TODO: Fix this
    /**
     * @param $field
     * @param $post_type
     */
    function QuickEditFields($field, $post_type)
    {
        foreach ($this->fields as $fi)
        {
            if ($fi->id == $field)
            {
                $fi->EditView($post_type);
                break;
            }
        }
    }

    # Display the edit fields
    /**
     *Iterating through the available fields and getting the edit view version. Also takes into account permission set of the field
     */
    function EditFields()
    {
        foreach ($this->fields as $fi)
        {
            if ( $fi->permissions->CanEdit($this->GetViewState()) !== false || $fi->permissions->CanCreate($this->GetViewState())  !== false){

                add_meta_box($fi->id, $fi->label, [$fi, "EditView"], $this->slug);
            }
            else if ($fi->permissions->CanRead($this->GetViewState()) !== false || $fi->permissions->CanRead($this->GetViewState())!== false)
            {
                add_meta_box($fi->id, $fi->label, [$fi, "ReadView"], $this->slug);
            }
        }
    }

    # Save post type fields
    /**
     * Method responsible for saving/updating fields. Does this by calling the PostData method on each field
     * @param $post_id
     */
    function SaveFields($post_id)
    {
        $data = [];
        $processed_data = [];

        foreach ($this->fields as $fi)
        {

            if ((!isset($_POST[$fi->saveNonce])
                || (! wp_verify_nonce($_POST[$fi->saveNonce], $fi->saveFunction))
                || (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
                || (! current_user_can('edit_post', $post_id))
            )){continue;}

            if (array_key_exists($fi->id, $_POST)) {
                if (is_array($_POST[$fi->id])) {
                    $sanitized_array = [];
                    foreach ($_POST[$fi->id] as $opt) {
                        array_push($sanitized_array, sanitize_text_field($opt));
                    }
                    $data[$fi->id] = $sanitized_array;
                }
                else{
                    $data[$fi->id] = sanitize_text_field($_POST[$fi->id]);
                }

            }
            elseif (array_key_exists("tinymce_".$fi->id, $_POST)) {
                //TODO: Consider using sanitize_textarea_field for late version
                $data[$fi->id] = sanitize_text_field($_POST["tinymce_".$fi->id]);
            }
            else{
                $data[$fi->id] = null;
            }

        }

        foreach ($data as $key => $value){
            $processed_data[$key] = $value;
        }

        if (count($data) > 0) {
            foreach ($this->BeforeSaveEvents as $observor) {
                //TODO: consider removing slug in $data
                foreach (call_user_func_array($observor, [$data]) as $key => $value) {
                    $field_key = sprintf("%s_%s", $this->slug, $key);

                    if (array_key_exists($field_key, $this->fields)) {
                        $_POST[$field_key] = $value;
                        $processed_data[$key] = $value;
                    }
                }
            }

            foreach ($this->fields as $fi) {
                if (array_key_exists($fi->id, $_POST) || array_key_exists("tinymce_" . $fi->id, $_POST) || $fi instanceof Checkbox) {
                    $fi->ProcessPostData($post_id);
                }
            }

            foreach ($this->AfterSaveEvents as $observor) {
                call_user_func_array($observor, [$processed_data]);
            }
        }
    }

    /**
     * Method used to register before save events
     * @param $method
     * @param null $class
     */
    public function RegisterBeforeSaveEvent($method, $class=null)
    {
        if ($class == null)
        {
            array_push($this->BeforeSaveEvents,  $method);
        }
        else
        {
            array_push($this->BeforeSaveEvents, [$class, $method]);

        }
    }

    /**
     * Method used to register after save events
     * @param $method
     * @param null $class
     */
    public function RegisterAfterSaveEvent($method, $class=null)
    {
        if ($class == null)
        {
            array_push($this->AfterSaveEvents,  $method);
        }
        else
        {
            array_push($this->AfterSaveEvents, [$class, $method]);

        }
    }

    /**
     * Method used to register before fetch events
     * @param $method
     * @param null $class
     */
    public function RegisterBeforeDataFetch($method, $class=null)
    {
        if (count($this->BeforeDataFetch) == 0) {
            add_action('pre_get_posts', [$this, "BeforeDataFetch"]);
        }

        if ($class == null)
        {
            array_push($this->BeforeDataFetch,  $method);
        }
        else
        {
            array_push($this->BeforeDataFetch, [$class, $method]);

        }

    }


}