<?php

namespace wpOOW\Core\RestApi\Validators;

class InjectionsValidator extends RestApiValidator{
    function validate($request, $routeInstance)
    {
        return $request;
    }
}