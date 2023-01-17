<?php

namespace wpOOW\Core\RestApi\Validators;

abstract class RestApiValidator
{
    abstract function validate($request, $routeInstance);
}