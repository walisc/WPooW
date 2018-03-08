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
    protected $elementIds = [
        "selected_file_preview" =>"_selected_file_preview",
        "selected_file_display" => "_selected_file_display",
        "upload_button" => "_upload_button",
        "selected_file" => "_selected_file"
    ];

    function __construct($id, $label, $permissions=null, $uploaderTitle = "Select Item to Upload", $buttonText= "Upload", $enableMultiple = "false", $elementPath='', $elementCssClasses=[])
    {

        wp_enqueue_media();
        wp_register_script("wpAPIMediaUploader",  $this->GetElementURIDirectory()  . "wpAPIMediaUploader.js",  ["jquery"], "1.0.0", true);
        

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
        $fileData = json_decode($this->GetDatabaseValue($post_id), true);
        $fileData = $fileData != null ? $fileData : [];

        $fileId = array_key_exists("id", $fileData) ? $fileData["id"] : "";
        $fileName = array_key_exists("filename", $fileData) ? $fileData["filename"] : "";
        echo $this->twigTemplate->render(get_class($this).'/read_view.mustache', ["filePreview" => wp_get_attachment_image($fileId),
            "fileName" => $fileName]);

    }

    function EditView( $post)
    {
        parent::EditView($post);

        $wpAPIMediaUploaderBagCount = "wpAPI_uploaderJsData_bag_count";
        
        wp_cache_add($wpAPIMediaUploaderBagCount, 1);
        $mediaUploaderIndex =wp_cache_get($wpAPIMediaUploaderBagCount);

        wp_localize_script("wpAPIMediaUploader", "uploaderJsData_bag_". $mediaUploaderIndex,
                    array_merge(["id" =>$this->id,
                    "title" => $this->uploaderTitle,
                    "buttonText" => $this->buttonText,
                    "multiple" => $this->enableMultiple
                    ],$this->elementIds));

        wp_enqueue_script("wpAPIMediaUploader");
        wp_cache_set($wpAPIMediaUploaderBagCount, ++$mediaUploaderIndex);
        
        $fileData = json_decode($this->GetDatabaseValue($post->ID), true);
        $fileData = $fileData != null ? $fileData : [];

        $fileId = array_key_exists("id", $fileData) ? $fileData["id"] : "";
        $fileName = array_key_exists("filename", $fileData) ? $fileData["filename"] : "";

        echo $this->twigTemplate->render(get_class($this).'/edit_view.mustache', [
            "id" => $this->id,
            "buttonText" => $this->buttonText,
            "value" => $this->GetDatabaseValue($post->ID),
            "cssClass" => implode(" ", $this->cssClasses),
            "fileDetails" => [
            "filename" => $fileName,
            "fileId" =>$fileId,
            "filePreview" => wp_get_attachment_image($fileId)],
            "elementIds" => $this->elementIds
        ]);
    }

    function ProcessPostData($post_id)
    {
        parent::ProcessPostData($post_id);
        $data = sanitize_text_field($_POST[$this->id]);

        $this->SaveElementData($post_id, $data);

    }
}