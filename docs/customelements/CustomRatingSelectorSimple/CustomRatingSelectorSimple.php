<?php

class CustomRatingSelectorSimple extends BaseElement{

    function BaseScriptsToLoad( )
    {
        $element_uri_path = wpAPIUtilities::GetWpAPUriLocation(dirname(__FILE__)). "/";
        
        $this->EnqueueElementBaseScript("jquery_ui", $element_uri_path  . "libs/jquery-ui.min.js",[], ["jquery"], "1.0.0", true);
        $this->EnqueueElementBaseCSS("jquery_ui_css", $element_uri_path . "libs/jquery-ui.min.css", [], "1.0.0", $media = 'all');
        $this->EnqueueElementBaseCSS("rating_selector_simple_css", $element_uri_path . "CustomRatingSelector.css", [], "1.0.0", $media = 'all');
    }

    function ReadView($post)
    {
        echo $this->twigTemplate->render("/read_view.twig", [
        "element_id" => $this->id,
        "value" => $this->GetDatabaseValue($post)
    ]);
    }

    function EditView($post)
    {
        parent::EditView($post);
        
        $this->EnqueueElementScript("/CustomRatingSelector.element.js", ["element_id" => $this->id]);

        echo $this->twigTemplate->render("/edit_view.twig", [
            "element_id" => $this->id,
            "value" => $this->GetDatabaseValue($post)
        ]);

    }

    function ProcessPostData($post_id)
    {
        parent::ProcessPostData($post_id);
        $data = sanitize_text_field($_POST[$this->id]);

        $this->SaveElementData($post_id, $data);

    }


}