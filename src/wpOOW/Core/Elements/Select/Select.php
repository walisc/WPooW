<?php

namespace wpOOW\Core\Elements\Select;


use wpOOW\Core\Elements\BaseElement;

/**
 * Class Select
 *
 * @package wpAPI\Core\Elements\Select
 */
class Select extends BaseElement
{
    //Value -> label
    public $options = [];

    /**
     * Select constructor.
     * @param $id - See BaseElement definitions
     * @param string $label - See BaseElement definitions
     * @param array $options - [value => label] array. Eg [value1 => label1, value2 => label2]
     * @param array $permissions - See BaseElement definitions
     * @param string $elementPath - See BaseElement definitions
     */
    function __construct($id, $label, $options, $permissions=[], $elementPath='')
    {
        parent::__construct($id, $label, $permissions, $elementPath);
        $this->options = $options;
    }

    function ReadView($post)
    {
        $value =$this->GetDatabaseValue($post);

        if (is_array($value))
        {
            $value = array_keys($value)[0]; 
        }

        if (array_key_exists($value, $this->options))
        {
            $value = $this->options[$value];
        }
        echo $this->twigTemplate->render('/read_view.mustache', ["value" => $value]);
    }

    function EditView( $post)
    {
       parent::EditView($post);

       $value =$this->GetDatabaseValue($post);

        if (is_array($value))
        {
            $value = array_keys($value)[0]; 
        }

       echo $this->twigTemplate->render('/edit_view.mustache', ["options" => $this->options,
                                                                                "id" => $this->id,
                                                                                "selected_option" => $value ]);
    }

    /**
     * Value of selected item is saved
     * @param $post_id
     */
    function ProcessPostData($post_id)
    {
        parent::ProcessPostData($post_id);
        $data = sanitize_text_field($_POST[$this->id]);
        $value_key_array = [];

        if (array_key_exists($data, $this->options) && $data != "") {
            $value_key_array[$data] = $this->options[$data];
        }

        $this->SaveElementData($post_id, $value_key_array);
        
    }
}