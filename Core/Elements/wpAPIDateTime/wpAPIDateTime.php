<?php


/**
 * Class wpAPIDateTime
 *
 * @package wpAPI\Core\Elements\wpAPIDateTime
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
       $db_dateTime = $this->GetDatabaseValue($post) == null ?  date("Y-m-d\TH:i:s", time()) : $this->GetDatabaseValue($post);
    
       
       echo $this->twigTemplate->render('/edit_view.mustache', [
           "id" => $this->id,
           "value" => $db_dateTime
       ]);
    }

    function ProcessPostData($post_id)
    {
        parent::ProcessPostData($post_id);
        $data = sanitize_text_field($_POST[$this->id]);

        $this->SaveElementData($post_id, $data);
        
    }
}