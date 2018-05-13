<?php


/**
 * Class Text
 *
 *
 * @package wpAPI\Core\Elements\Text
 */
class Text extends BaseElement
{
    
    function __construct($id, $label, $permissions=[], $elementPath='')
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
           "label" => $this->label,
           "value" => $this->GetDatabaseValue($post)
       ]);
    }

    /**
     * Text data is saved as a  string 
     * @param $post_id
     */
    function ProcessPostData($post_id)
    {
        parent::ProcessPostData($post_id);
        $data = sanitize_text_field($_POST[$this->id]);

        $this->SaveElementData($post_id, $data);
        
    }
}