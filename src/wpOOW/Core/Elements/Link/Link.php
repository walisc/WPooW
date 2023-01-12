<?php


/**
 * Class Link
 *
 * @package wpAPI\Core\Elements\Link
 */
class Link extends BaseElement
{
    protected $elementIds = ["link_label" =>"_link_label",
                            "link_url" => "_link_url"
                            ];

    function __construct($id, $label, $permissions=[], $elementPath='')
    {
        parent::__construct($id, $label, $permissions, $elementPath);
    }

    function ReadView($post)
    {
        $linkData = json_decode($this->GetDatabaseValue($post), true);
        echo $this->twigTemplate->render('/read_view.mustache', ["url" => $linkData["url"], "url_label" => $linkData["url_label"]]);
    }

    function EditView( $post)
    {
       parent::EditView($post);

       $linkData = json_decode($this->GetDatabaseValue($post), true);

       echo $this->twigTemplate->render('/edit_view.mustache', [
           "id" => $this->id,
           "elementIds" => $this->elementIds,
           "link_value_url" => $linkData["url"],
           "link_value_label" => $linkData["url_label"],
       ]);
    }

    //TODO: Add target property
    /**
     * Data is saved as a json string with the format
     *
     * ` {
     *       url_label: [url_label], //The label of the link
     *       url: [url], //The url it navigates to
     *   }
     *   `
     *
     * @param $post_id
     */
    function ProcessPostData($post_id)
    {
        parent::ProcessPostData($post_id);


        $database_value = [
            "url_label" => sanitize_text_field($_POST[sprintf("%s%s",$this->id, $this->elementIds["link_label"])]),
            "url" => sanitize_text_field($_POST[sprintf("%s%s",$this->id, $this->elementIds["link_url"])])

        ];

        $this->SaveElementData($post_id, json_encode($database_value));
        
    }
}