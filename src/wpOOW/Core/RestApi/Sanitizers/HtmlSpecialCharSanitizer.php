<?php

namespace wpOOW\Core\RestApi\Sanitizers;

class HtmlSpecialCharSanitizer extends RestApiSanitizers{
    function sanitize($request, $routeInstance)
    {
        foreach ($request->get_params()  as $param => $value){
            $request->set_param($param, htmlspecialchars($value, ENT_NOQUOTES | ENT_SUBSTITUTE | ENT_HTML401)); 
        }
        return $request;
    }
}