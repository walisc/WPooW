<?php

class CustomRatingsSelector extends BaseElement{

    function BaseScriptsToLoad( )
    {
        $element_uri_path = wpAPIUtilities::GetWpAPUriLocation(dirname(__FILE__)). "/";
        
        $this->EnqueueElementBaseScript("jquery_ui", $element_uri_path  . "libs/jquery-ui.min.js",[], ["jquery"], "1.0.0", true);
        $this->EnqueueElementBaseCSS("jquery_ui_css", $element_uri_path . "libs/jquery-ui.min.css", [], "1.0.0", $media = 'all');
        $this->EnqueueElementBaseCSS("rating_selector_css", $element_uri_path . "CustomRatingSelector.css", [], "1.0.0", $media = 'all');
    }

    function ReadView($post)
    {
        echo $this->GetDatabaseValue($post);
    }

    function EditView($post)
    {
        parent::EditView($post);
        $options = $this->GetOptions();

        $saved_values = json_decode($this->GetDatabaseValue($post));
        $saved_values_obj = is_array($saved_values) ? $saved_values : [];

        foreach ($saved_values_obj as $category => $value)
        {
            if (array_key_exists($category, $options))
            {
                $options[$category] = $value;
            }
        }

        $this->EnqueueElementScript("/CustomRatingSelector.element.js", ["element_id" => $this->id]);

        echo $this->twigTemplate->render("/edit_view.twig", [
            "element_id" => $this->id,
            "options" => $options
        ]);



    }

    protected function GetOptions(){
        return [
          "well_written" => [
              "score" => 0,
              "label" => "Well Written"
          ]  ,
          "well_developed" => [
              "score" => 0,
              "label" => "Well Developed"
          ]  ,
          "captivating" => [
              "score" => 0,
              "label" => "Captivating"
          ]  ,
          "worth_sharing" => [
              "score" => 0,
              "label" => "Worth Sharing"
          ]
        ];
    }
}