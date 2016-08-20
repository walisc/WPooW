<?php

/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/08/19
 * Time: 10:58 PM
 */
class Uploader extends BaseElement
{

    private $uploaderTitle;
    private $buttonText;
    private $enableMultiple;

    function __construct($id, $label, ElementPermission $permissions, $uploaderTitle = "Select Item to Upload", $buttonText= "Upload", $enableMultiple = "false", $elementPath='', $elementCssClasses=[])
    {

        wp_enqueue_media();
        wp_register_script("wpAPIMediaUploader", DIRECTORY_SEPARATOR.  $this->GetElementDirectory()  . "wpAPIMediaUploader.js",  ["jquery"], "1.0.0", true);
        

        if (empty($elementCssClasses))
        {
            $elementCssClasses = [
                "button",
                "button-primary"
            ];
        }

        $this->uploaderTitle = $uploaderTitle;
        $this->buttonText = $buttonText;
        $this->enableMultiple = $enableMultiple;

        parent::__construct($id, $label, $permissions, $elementPath, $elementCssClasses);
    }

    function ReadView($post_id)
    {
        echo $this->mustache->render('Uploader/read_view.mustache', ["value" => $this->GetDatabaseValue($post_id)]);
    }

    function EditView( $post)
    {
        parent::EditView($post);
        
        wp_localize_script("wpAPIMediaUploader", "uploaderJsData", ["id" =>$this->id, "title" => $this->uploaderTitle, "buttonText" => $this->buttonText, "multiple" => $this->enableMultiple]);
        wp_enqueue_script("wpAPIMediaUploader");

        echo $this->mustache->render('Uploader/edit_view.mustache', [
            "id" => $this->id,
            "buttonText" => $this->buttonText,
            "value" => $this->GetDatabaseValue($post->ID),
            "cssClass" => implode(" ", $this->cssClasses)
        ]);
    }

    function ProcessPostData($post_id)
    {
        parent::ProcessPostData($post_id);
        $data = sanitize_text_field($_POST[$this->id]);

        $this->SaveElementData($post_id, $data);

    }
}