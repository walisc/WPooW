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

    function __construct($slug, $label, $options = [])
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
    }

    private function CreateProperties()
    {
        foreach ($this->props as $opt => $val){
            $this->{$opt} = $val;
        }
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
        add_action('save_post', [$this, "SaveFields"]);

    }
    function RenderHook()
    {
        return 'init';
    }

    function Render($parent_slug)
    {
        if ($parent_slug != null)
        {
            $this->show_in_menu = $parent_slug;
        }
        parent::Render($parent_slug);
    }

    public function AddField($aField)
    {
        $aField->parent_slug = $this->slug;
        $aField->id = sprintf("%s_%s",$this->slug, $aField->id);
        $aField->saveFunction = sprintf("save_data_%s",  $aField->id);
        $aField->saveNonce = sprintf("%s_meta_box_nonce",$aField->id);
        $aField->valueKey = sprintf("%s_value_key", $aField->id);

        array_push($this->fields, $aField);
    }

    function SetFields( $fields)
    {

        $postTypeFields = [];

        foreach ($this->fields as $fi)
        {
            $postTypeFields[$fi->id] = $fi->label;
        }

        return $postTypeFields;
    }

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

    function EditFields()
    {
        foreach ($this->fields as $fi)
        {
            if ($fi->permissions->UPDATE)
            {
                add_meta_box($fi->id, $fi->label, [$fi, "EditView"] , $this->slug);
            }
        }
    }

    function SaveFields($post_id)
    {
        foreach ($this->fields as $fi)
        {
            if ($fi->permissions->UPDATE)
            {
                $fi->ProcessPostData($post_id);
            }
        }
    }
}