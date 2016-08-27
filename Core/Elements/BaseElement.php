<?php

/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/08/13
 * Time: 7:36 PM
 */
abstract class BaseElement
{
    //TODO: Have a way of checking that id is unique
    public $parent_slug;
    public $id;
    public $label;
    public $permissions;
    protected $twigTemplate;
    protected $cssClasses;

    public $saveFunction;
    public $saveNonce;
    public $valueKey;

    private $onSaveEvents = [];
    private $onViewEvents = [];
    private $onEditEvents = [];


    protected function ReadView($post_id)
    {
        $db_value = $this->GetDatabaseValue($post_id);

        foreach ($this->onViewEvents as $observor)
        {
            call_user_func_array($observor, [$db_value, $post_id]);
        }
    }
    protected function EditView( $post)
    {
        wp_nonce_field($this->saveFunction, $this->saveNonce);
    }

    protected function SaveElementData($post_id, $data)
    {
        update_post_meta($post_id, $this->valueKey, $data);
    }

    protected function GetDatabaseValue($post_id)
    {
        $db_value =  get_post_meta($post_id, $this->valueKey, true);

        foreach ($this->onViewEvents as $observor)
        {
            $db_value =  call_user_func_array($observor, [$db_value, $post_id]);
        }
        return $db_value;
    }

    protected function GetElementDirectory()
    {
        return WP_API_ELEMENT_PATH_REL.  get_class($this) . DIRECTORY_SEPARATOR;
    }

    public function RegisterOnSaveEvent($class, $method)
    {
        array_push($this->onSaveEvents, [$class, $method ]);
    }

    public function RegisterOnViewEvent($class, $method)
    {
        array_push($this->onViewEvents, [$class, $method ]);
    }

    public function RegisterOnEditEvent($class, $method)
    {

    }
    public function ProcessPostData($post_id)
    {

        if ((!isset($_POST[$this->saveNonce])
        || (! wp_verify_nonce($_POST[$this->saveNonce], $this->saveFunction))
        || (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        || (! current_user_can('edit_post', $post_id))
        )){return;}
     }

    function __construct($id, $label="", ElementPermission $permissions, $elementPath = '', $elementCssClasses=[])
    {
        $this->id = $id;
        $this->label = $label;
        $this->permissions= $permissions;
        $this->cssClasses = $elementCssClasses;
        $this->saveFunction = sprintf("save_data_%s",  $this->id);
        $this->saveNonce = sprintf("%s_meta_box_nonce",$this->id);
        $this->valueKey = sprintf("%s_value_key", $this->id);

        //TODO: Make this global

        $loader = new Twig_Loader_Filesystem(__DIR__. $elementPath);
        $this->twigTemplate = new Twig_Environment($loader);
        $this->twigTemplate->addGlobal('wpAPIElementHelper', new wpAPIElementHelper());

        wpAPIObjects::GetInstance()->AddObject(sprintf("_element_%s", $this->id), $this);

    }
}

class wpAPIElementHelper{

    public function GetAttachmentThumbnail($id)
    {
        return wp_get_attachment_image($id);
    }
}


class ElementPermission
{
    public $READ = true;
    public $UPDATE =  true;

}