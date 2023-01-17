<?php

namespace wpOOW\Core\RestApi;

abstract class RestApiController
{
    private $restAPIRoutes;
    private $wpOOWInstance;
    private $namespaceId;


    public function __construct($namespaceId, $wpOoWInstance)
    {
        $this->namespaceId = $namespaceId;
        $this->wpOOWInstance = $wpOoWInstance;
        $this->restAPIRoutes = $this->GetRestApiRoutesToLoad();
    }

    /**
     * @return array<string,RestApiRoute>
     */
    abstract protected function GetRestApiRoutesToLoad(): array;

    public function GetRestAPIRoutes(){
        return $this->restAPIRoutes;
    }
    public function GetRestApiRoute($routeIdentifier){
        if (!array_key_exists($routeIdentifier, $this->restAPIRoutes)){
            throw new \Exception(sprintf("The route '%s' for the route namespace '%s' is unknown.", $routeIdentifier, $this->namespaceId)); // TODO: Do this better, Maybe log info
        }
        return $this->restAPIRoutes[$routeIdentifier];
    }
}