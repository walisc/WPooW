<?php

namespace wpOOW\Core\RestApi;
use wpOOW\Core\RestApi\Sanitizers\JsonSanitizer;
use wpOOW\Core\RestApi\Validators\InjectionsValidator;
use wpOOW\Core\RestApi\Validators\NonceValidator;

class RestApiRoute{

    private $route;
    private $restMethod;
    private $callBack;
    private $routeParams;
    private $permissionCallback;
    private $validators = [];
    private $sanitizers = [];

    private $parentNamespace = null;
    /**
     * @param $restMethod
     * @param string $route
     * @param RestApiRouteParam[] $routeParams
     * @param $callBack
     * @param $permissionCallback
     * @param $validatorCallback
     */
    public function __construct($restMethod, $route, $routeParams, $callBack, $permissionCallback, $validatorCallback=null, $sanitizerCallback=null )
    {
        if (!in_array($restMethod, [RestApiMethods::GET, RestApiMethods::POST])){
            throw new \Exception(sprintf("The rest method types need to be %s. %s selected.", join(", ",[RestApiMethods::GET, RestApiMethods::POST]), $restMethod));
        }

        $this->restMethod = $restMethod;
        $this->route = $route;
        $this->routeParams = $routeParams;
        $this->callBack = $callBack;
        $this->permissionCallback = $permissionCallback;

        $this->validators = [new NonceValidator(), new InjectionsValidator()];
        if (isset($validatorCallback)){
            $this->validators[] =  $validatorCallback;
        }

        $this->sanitizers = [new JsonSanitizer()];
        if (isset($sanitizerCallback)){
            $this->sanitizers[] =  $sanitizerCallback;
        }


    }
    function GetRoute(){
        return $this->route;
    }
    function GetMethod(){
        return $this->restMethod;
    }

    function GetCallback(){
        return [$this, "WrapperCallBack"];
    }

    function GetRouteParams(){
        return $this->routeParams;
    }
    function GetPermissions(){
        return $this->permissionCallback;
    }
    function GetValidators(){
        return $this->validators;
    }

    function SetRestApiParentId($parentNamespace){
        $this->parentNamespace = $parentNamespace;
    }

    protected function GetRouteNonce(){
        $this->CheckLoaded();
        return wp_create_nonce($this->GetNonceId());
    }

    function GenerateUrl($suffix=""){
        $this->CheckLoaded();
        return sprintf("%s/wp-json/%s/%s/%s%s%s", site_url(), $this->parentNamespace, $this->route, $suffix, strpos($suffix, '?') !== false ? "&" : "?" , '_wp_nounce='.$this->GetRouteNonce());
    }

    function WrapperCallBack( $request){
        $this->CheckLoaded();
        try{
            foreach ($this->validators as $validator){
                $request = $validator->validate($request, $this);
            }

            foreach ($this->sanitizers as $sanitizer){
                $request = $sanitizer->sanitize($request, $this);
            }
            $response = call_user_func_array($this->callBack, [$request]);
            if (!($response instanceof \WP_REST_Response || $response instanceof \WP_Error)){
                throw new \Exception(sprintf("Rest api responses muct be of type WP_REST_Response or WP_Error"));
            }
            return $response;

        }catch(\Exception $e){
            $this->SendErrorResponse();
        }
    }

    function CheckLoaded(){
        if (!isset($this->parentNamespace)){
            throw new \Exception(sprintf("Trying to use a method that require all route to be loaded first. Call Listen on the parent wpRestApi object."));
        }
    }

    private function GetNonceId(){
        $this->CheckLoaded();
        return sprintf("%s_%s", $this->parentNamespace, $this->route);
    }

    private function SendErrorResponse(){
        return new WP_Error( 'server_error', 'An error occurred whilst processing the request', array( 'status' => 500 ) );
    }
}

