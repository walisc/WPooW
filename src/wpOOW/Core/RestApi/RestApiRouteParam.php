<?php

namespace wpOOW\Core\RestApi;

class RestApiRouteParam{

    private $id;
    private $default;
    private $validateCallback;
    private $sanitizeCallback;

    public function __construct($id, $default=null, $validateCallback=null, $sanitizeCallback=null)
    {
        $this->id = $id;
        $this->default = $default;
        $this->validateCallback = $validateCallback;
        $this->sanitizeCallback = $sanitizeCallback;
    }

    function GetIdentifier(){
        return $this->id;
    }

    function GetPropsAsWPObject(){
        $paramsArray = [];

        //TODO: Consider having core validators/sanitizier. We do this already at the request level, but might be worth doing it here as well
        if ($this->default) {$paramsArray["default"] = $this->default;}
        if ($this->validateCallback) {$paramsArray["sanitization_callback"] = $this->validateCallback;}
        if ($this->sanitizeCallback) {$paramsArray["validation_callback"] = $this->sanitizeCallback;}

        return $paramsArray;
    }
}
