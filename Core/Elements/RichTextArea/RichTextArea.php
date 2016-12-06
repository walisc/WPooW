<?php

/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/08/13
 * Time: 7:46 PM
 */



class RichTextArea extends BaseElement
{

    function __construct($id, $label, $permissions=null, $elementPath='')
    {
        //wp_register_script("TinymceJs", DIRECTORY_SEPARATOR.  $this->GetElementDirectory()  . "tinymce/tinymce.min.js",  ["jquery"], "1.0.0");
       // wp_register_script("wpAPIRichTextJs", DIRECTORY_SEPARATOR.  $this->GetElementDirectory()  . "wpAPIRichText.js",  ["jquery"], "1.0.0", true);

        parent::__construct($id, $label, $permissions, $elementPath);
    }

    function ReadView($post_id)
    {
        echo $this->twigTemplate->render(get_class($this).'/read_view.mustache', ["value" => $this->GetDatabaseValue($post_id)]);
    }

    function EditView( $post)
    {
       parent::EditView($post);

      // wp_enqueue_script("TinymceJs");

      // wp_localize_script("wpAPIRichTextJs", "wpAPIRichTextJsData", ["id" =>$this->id]);
     //  wp_enqueue_script("wpAPIRichTextJs");

        echo $this->twigTemplate->render(get_class($this).'/edit_view.mustache', [
           "id" => $this->id,
           "label" => $this->label,
           "value" => $this->GetDatabaseValue($post->ID)
       ]);

      //  wp_editor( $this->GetDatabaseValue($post->ID), $this->id );
    }

    function ProcessPostData($post_id)
    {
        parent::ProcessPostData($post_id);
        $data = sanitize_text_field($_POST[$this->id]);

        $this->SaveElementData($post_id, $data);
        
    }
}