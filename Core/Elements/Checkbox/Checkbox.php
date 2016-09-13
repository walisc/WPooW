<?php

/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/08/13
 * Time: 7:46 PM
 */



class Checkbox extends BaseElement
{

    function __construct($id, $label, $permissions=null, $elementPath='')
    {
        parent::__construct($id, $label, $permissions, $elementPath);
    }

    function ReadView($post_id)
    {
        $activeValue = $this->GetDatabaseValue($post_id) == 1 ? "checked" : "";
        echo $this->twigTemplate->render(get_class($this).'/read_view.mustache', ["active_value" => $activeValue]);
    }

    function EditView( $post)
    {
       parent::EditView($post);

       $activeValue = $this->GetDatabaseValue($post->ID) == "on" ? "checked" : "";
       echo $this->twigTemplate->render(get_class($this).'/edit_view.mustache', ["active_value" => $activeValue,
                                                                                "id" => $this->id]);
    }

    function ProcessPostData($post_id)
    {
        parent::ProcessPostData($post_id);
        $data = sanitize_text_field($_POST[$this->id]);

        $this->SaveElementData($post_id, $data);
        
    }
}