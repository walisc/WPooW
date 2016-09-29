<?php

/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/08/13
 * Time: 7:46 PM
 */



class Select extends BaseElement
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

        if (array_key_exists($value, $this->options))
        {
            $value = $this->options[$value];
        }
        echo $this->twigTemplate->render(get_class($this).'/read_view.mustache', ["value" => $value]);
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
        $data = sanitize_text_field($_POST[$this->id]);

        $this->SaveElementData($post_id, $data);
        
    }
}