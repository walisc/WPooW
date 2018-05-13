<?php


/**
 * Class RichTextArea
 *
 * @package wpAPI\Core\Elements\RichTextArea
 */
class RichTextArea extends BaseElement
{

    function __construct($id, $label, $permissions=[], $elementPath='')
    {
        parent::__construct($id, $label, $permissions, $elementPath);
    }

    function ReadView($post)
    {
        echo $this->twigTemplate->render('/read_view.mustache', ["value" => html_entity_decode($this->GetDatabaseValue($post))]);
    }

    function EditView( $post)
    {
       parent::EditView($post);

        wp_editor(  html_entity_decode($this->GetDatabaseValue($post)) , "tinymce_".$this->id, array(
            'tinymce' => true
        ) ); #TODO Consider making wpautop configurable
    }

    function ProcessPostData($post_id)
    {
        parent::ProcessPostData($post_id);
        $data = esc_html($_POST["tinymce_".$this->id]);

        $this->SaveElementData($post_id, $data);
        
    }
}