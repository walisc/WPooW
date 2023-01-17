<?php

namespace wpOOW\Core\RestApi;

class wpRestApi{

    /**
     * @var array<string,RestApiController>
     */
    private $routeControllers;
    private $restApiNamespace;
    public function __construct($restApiNamespace)
    {
        $this->restApiNamespace = $restApiNamespace;
    }


    private function GetRestApiNameSpace($controlleRouteId){
        return sprintf("%s/%s", $this->restApiNamespace, $controlleRouteId);
    }
    /**
     * @param $route_id
     * @param RestApiController $controller
     * @return void
     */
    public function AddRouteController($route_id, RestApiController $controller){
        $this->routeControllers[$route_id] = $controller; // TODO: Ensure lowercase

        foreach ($this->routeControllers[$route_id]->GetRestAPIRoutes() as $route_id => $routeDetail){
            $routeDetail->SetRestApiParentId($this->GetRestApiNameSpace($route_id));
        }
    }

    public function Listen(){
        add_action( 'rest_api_init', [$this, "LoadRestApiRoutes"]);
    }

    public function LoadRestApiRoutes(){
        foreach ($this->routeControllers as $controllerRouteId => $routeController ){
            foreach ($routeController->GetRestAPIRoutes() as $route_id => $routeDetail)
                

                register_rest_route( $this->GetRestApiNameSpace($controllerRouteId) , $routeDetail->GetRoute(), array(
                    'methods' =>  $routeDetail->GetMethod(),
                    'callback' => $routeDetail->GetCallback(),
                    'args' =>  array_reduce($routeDetail->GetRouteParams(), function ($accumulator, $routeParam) {
                        $accumulator[$routeParam->GetIdentifier()] = $routeParam->GetPropsAsWPObject();
                        return $accumulator;
                    }, []), 
                    'permission_callback' => $routeDetail->GetPermissions()
                ) );
        }
    }

}