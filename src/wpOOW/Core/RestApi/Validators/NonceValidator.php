<?php

namespace wpOOW\Core\RestApi\Validators;

class NonceValidator extends RestApiValidator{
    function validate($request, $routeInstance)
    {
        if (!isset($_GET["_wp_nounce"])){
            return new \WP_Error( 'server_error', 'Request does not seem to be from the server', array( 'status' => 400 ) );
        }
        if (!wp_verify_nonce($_GET["_wp_nounce"], $routeInstance->GetNonceId())){
            return new \WP_Error( 'server_error', 'Request does not seem to be from the server', array( 'status' => 400 ) );
        }

        return $request;
    }
}