<?php

/**
 *
 * @package wpAPI\Core\Elements
 */
class Checkbox extends BaseElement
{

    /**
     * Checkbox constructor.
     * @param $id
     * @param string $label
     * @param array $permissions
     * @param string $elementPath
     */
    function __construct($id, $label, $permissions=[], $elementPath='')
    {
        parent::__construct($id, $label, $permissions, $elementPath);
    }

    /**
     * @param $post
     */
    function ReadView($post)
    {
        $activeValue = $this->GetDatabaseValue($post) == "on" ? "checked" : "";
        echo $this->twigTemplate->render(get_class($this).'/read_view.mustache', ["active_value" => $activeValue]);
    }

    /**
     * @param $post
     */
    function EditView($post)
    {
       parent::EditView($post);

       $activeValue = $this->GetDatabaseValue($post) == "on" ? "checked" : "";
       echo $this->twigTemplate->render(get_class($this).'/edit_view.mustache', ["active_value" => $activeValue,
                                                                                "id" => $this->id]);
    }

    /**
     * @param $post_id
     */
    function ProcessPostData($post_id)
    {
        parent::ProcessPostData($post_id);
        $data = sanitize_text_field($_POST[$this->id]);

        $this->SaveElementData($post_id, $data);
        
    }
}