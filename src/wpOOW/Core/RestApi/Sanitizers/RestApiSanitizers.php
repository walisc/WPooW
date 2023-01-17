<?php

namespace wpOOW\Core\RestApi\Sanitizers;

abstract class RestApiSanitizers
{
    abstract function sanitize($request, $routeInstance);
}