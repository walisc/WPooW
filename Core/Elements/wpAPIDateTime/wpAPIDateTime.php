<?php

/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/08/13
 * Time: 7:46 PM
 */


class wpAPIDateTime extends BaseElement
{

    function __construct($id, $label,  $permissions=[], $elementPath='')
    {
        parent::__construct($id, $label, $permissions, $elementPath);
    }

    function ReadView($post)
    {
        echo $this->twigTemplate->render('/read_view.mustache', ["value" => $this->GetDatabaseValue($post)]);
    }

    function EditView( $post)
    {
       parent::EditView($post);
       echo $this->twigTemplate->render('/edit_view.mustache', [
           "id" => $this->id,
           "value" => $this->GetDatabaseValue($post)
       ]);
    }

    function ProcessPostData($post_id)
    {
        parent::ProcessPostData($post_id);
        $data = sanitize_text_field($_POST[$this->id]);

        $this->SaveElementData($post_id, $data);
        
    }
}