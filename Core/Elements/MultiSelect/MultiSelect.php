<?php

/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/08/13
 * Time: 7:46 PM
 */



class MultiSelect extends BaseElement
{
    //Value -> label
    public $options = [];


    function __construct($id, $label, $options, $permissions=null, $elementPath='')
    {
        parent::__construct($id, $label, $permissions, $elementPath);
        $this->options = $options;
    }

    function ReadView($post_id)
    {
        $value =$this->GetDatabaseValue($post_id);
        $display_values = [];

        foreach ($value as $selected)
        {
            if (array_key_exists($selected, $this->options))
            {
                array_push($display_values, $this->options[$selected]);
            }
        }

        echo $this->twigTemplate->render(get_class($this).'/read_view.mustache', ["value" => implode(', ', $display_values)]);
    }

    function EditView( $post)
    {
       parent::EditView($post);

       echo $this->twigTemplate->render(get_class($this).'/edit_view.mustache', ["options" => $this->options,
                                                                                "id" => $this->id,
                                                                                "selected_option" => $this->GetDatabaseValue($post->ID) ]);
    }

    function ProcessPostData($post_id)
    {
        parent::ProcessPostData($post_id);
        $data = $_POST[$this->id];

        $this->SaveElementData($post_id, $data);
        
    }
}