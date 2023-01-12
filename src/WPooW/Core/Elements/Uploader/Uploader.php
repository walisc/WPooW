<?php


/**
 * Class Uploader
 *
 * @package wpAPI\Core\Elements\Uploader
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

    /**
     * Uploader constructor.
     * @param $id - See BaseElement definitions
     * @param string $label - See BaseElement definitions
     * @param array $permissions - See BaseElement definitions
     * @param string $uploaderTitle - The title of the media picker dialogue box
     * @param string $buttonText - the text on the upload button on the media picker
     * @param string $enableMultiple - Allowing for selecting multipe of items
     * @param string $elementPath - See BaseElement definitions
     * @param array $elementCssClasses - See BaseElement definitions
     */
    function __construct($id, $label, $permissions=[], $uploaderTitle = "Select Item to Upload", $buttonText= "Upload", $enableMultiple = "false", $elementPath='', $elementCssClasses=[])
    {

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

    function BaseScriptsToLoad(){
        wp_enqueue_media();
        $this->EnqueueElementBaseScript("wpOOWUploader",  $this->GetElementURIDirectory()  . "wpOOWUploader.js",  [], ["jquery"], "1.0.0", true);

    }

    function ReadView($post)
    {
        $fileData = json_decode($this->GetDatabaseValue($post), true);
        $fileData = $fileData != null ? $fileData : [];

        $fileId = array_key_exists("id", $fileData) ? $fileData["id"] : "";
        $fileName = array_key_exists("filename", $fileData) ? $fileData["filename"] : "";
        echo $this->twigTemplate->render('/read_view.mustache', ["filePreview" => wp_get_attachment_image($fileId),
            "fileName" => $fileName]);

    }

    function EditView( $post)
    {
        parent::EditView($post);

        $this->EnqueueElementScript('/wpOOWUploader.element.js',  array_merge(["id" =>$this->id,
                                                                                "title" => $this->uploaderTitle,
                                                                                "buttonText" => $this->buttonText,
                                                                                "multiple" => $this->enableMultiple
                                                                            ],$this->elementIds));

        $fileData = json_decode($this->GetDatabaseValue($post), true);
        $fileData = $fileData != null ? $fileData : [];

        $fileId = array_key_exists("id", $fileData) ? $fileData["id"] : "";
        $fileName = array_key_exists("filename", $fileData) ? $fileData["filename"] : "";

        echo $this->twigTemplate->render('/edit_view.mustache', [
            "id" => $this->id,
            "buttonText" => $this->buttonText,
            "value" => $this->GetDatabaseValue($post),
            "cssClass" => implode(" ", $this->cssClasses),
            "fileDetails" => [
            "filename" => $fileName,
            "fileId" =>$fileId,
            "filePreview" => wp_get_attachment_image($fileId)],
            "elementIds" => $this->elementIds
        ]);
    }

    /**
     * Uploader data is saved as a json string with the following signature
     *
     * ` {
     *       id: [id], //The field id in which the upload happened
     *       url: [url], //The url of the file
     *       filename: [name] // The name of the file
     *   }
     *   `
     *
     * @param $post_id
     */
    function ProcessPostData($post_id)
    {
        parent::ProcessPostData($post_id);
        $data = sanitize_text_field($_POST[$this->id]);

        $this->SaveElementData($post_id, $data);

    }

    function FormatForFetch($value, $recordId){
        #TODO Maybe create a hook that allows you modify this output
        //This is mainly for backward compatibility. Imaages uploaded with http now useing https
        return strpos($value, "https") == false && is_ssl() ? str_replace("http", "https", $value) : $value;
    }
}