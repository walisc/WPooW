<?php
/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/08/11
 * Time: 4:18 PM
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
        "supports" => ["title"]
    ];

    protected $fields = [];

    private $BeforeSaveEvents = [];
    private $BeforeDataFetch = [];
    private $AfterSaveEvents = [];
    private $onEditEvents = [];
    private $onViewEvents = [];

    private $persist = false;

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

    private function CreateProperties()
    {
        foreach ($this->props as $opt => $val){
            $this->{$opt} = $val;
        }
    }

    function Query()
    {
        return new wpQueryObject($this);
    }

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

    function BeforeDataFetch($query){
        if ($query->query["post_type"]== $this->GetSlug())
        {
            foreach ($this->BeforeDataFetch as $observor) {
                call_user_func_array($observor, [$query]);
            }
        }


    }

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

    function RenderHook()
    {
        return 'init';
    }

    function Render($parent_slug=null)
    {
        if ($parent_slug != null)
        {
            $this->show_in_menu = $this->props["show_in_menu"] ? $parent_slug : "";
        }
        parent::Render($parent_slug);
    }

    public function GetFields()
    {
        return $this->fields;
    }

    public function GetFieldDbKey($field_id)
    {

        return $this->fields[sprintf("%s_%s", $this->slug, $field_id)]->valueKey;
    }

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
    function SetFields( $fields)
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

            $data[$fi->id] = sanitize_text_field($_POST[$fi->id]);
        }

        foreach ($this->BeforeSaveEvents as $observor)
        {
            //TODO: consider removing slug in $data
            foreach (call_user_func_array($observor, [$data]) as $key => $value)
            {
                $field_key = sprintf("%s_%s", $this->slug, $key);

                if (array_key_exists($field_key, $this->fields)) {
                    $_POST[$field_key] = $value;
                    $processed_data[$key] = $value;
                }
            }
        }

        foreach ($this->fields as $fi)
        {
            if (array_key_exists($fi->id, $_POST) ||  array_key_exists("tinymce_".$fi->id, $_POST) || $fi instanceof Checkbox) {
                $fi->ProcessPostData($post_id);
            }
        }

        foreach ($this->AfterSaveEvents as $observor)
        {
            call_user_func_array($observor, [$processed_data == [] ? $data : $processed_data]);
        }
    }

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