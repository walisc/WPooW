<?php

/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/08/19
 * Time: 10:58 PM
 */
class Uploader extends BaseElement
{

    function __construct($id, $label, ElementPermission $permissions, $elementPath='')
    {
        wp_enqueue_media();
        wp_register_script("wpAPIMediaUploader", __DIR__ . "wpAPIMediaUploader.js",  ["jquery"], "1.0.0", true);
        wp_enqueue_script("wpAPIMediaUploader");

        parent::__construct($id, $label, $permissions, $elementPath);
    }

    function ReadView($post_id)
    {
        echo $this->mustache->render('Text/read_view.mustache', ["value" => $this->GetDatabaseValue($post_id)]);
    }

    function EditView( $post)
    {
        parent::EditView($post);
        echo $this->mustache->render('Text/edit_view.mustache', [
            "id" => $this->id,
            "label" => $this->label,
            "value" => $this->GetDatabaseValue($post->ID)
        ]);
    }

    function ProcessPostData($post_id)
    {
        parent::ProcessPostData($post_id);
        $data = sanitize_text_field($_POST[$this->id]);

        $this->SaveElementData($post_id, $data);

    }
}