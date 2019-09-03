<?php

namespace WPooWTests\CustomTestElements;


class TestGreetingElementBasic extends \BaseElement{

    public static function getTemplates(){
        return [
            'readView' => '<p>%s</p>',
            'editView' => '<p>Enter your name:</p> <input type="text" name="%s" value="%s"/>'
        ];
    }
    function ReadView($post)
    {
        echo sprintf(self::getTemplates()['readView'],$this->GetDatabaseValue($post));
    }

    function EditView($post)
    {
        parent::EditView($post);
        echo sprintf(self::getTemplates()['editView'],
                    $this->id,
                    $this->GetDatabaseValue($post));
    }

    function ProcessPostData($post_id)
    {
         parent::ProcessPostData($post_id);
            $data = sanitize_text_field($_POST[$this->id]);

            $this->SaveElementData($post_id, $data);
    }
}