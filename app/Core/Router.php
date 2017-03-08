<?php

namespace Kriptonic\App\Core;

/**
 * Class Router
 *
 * This class is for directing URIs to controllers and views.
 *
 * @package Kriptonic\App\Core
 * @author Christopher Sharman <christopher.p.sharman@gmail.com>
 */
class Router
{
    /**
     * A collection of routes and their controllers for each method type.
     * @var array The routes definition array.
     */
	private $routes = [];

    /**
     * Router constructor.
     *
     * @param string $routesFile The file path to the location to load routes from.
     */
	public function __construct($routesFile)
    {
        $this->load($routesFile);
    }

    /**
     * Load routes into the router from the specified file.
     *
     * @param string $routesFile A file containing route registrations.
     * @return Router The router object.
     * @throws \Exception Thrown when the route file couldn't be found.
     */
    public function load($routesFile)
    {
        if (!file_exists($routesFile)) {
            throw new \Exception("The routes file '$routesFile' does not exist");
        }

        $router = $this;

        require $routesFile;

        return $router;
    }

    /**
     * Run the code in the controller specified for the current method and URI and handle the response.
     *
     * @param string $method The current request method.
     * @param string $uri The current request URI.
     * @throws \Exception Thrown when a route could not be matched.
     */
	public function dispatch($method, $uri)
	{
	    if (!isset($this->routes[$method][$uri])) {
	        throw new \Exception("Route '$uri' is not supported for '$method' method");
        }

        $routeController = $this->routes[$method][$uri];

		$response = $this->handle($routeController);

		if ($response instanceof Response) {
		    $response->handle();
        } else {
		    print json_encode($response);
		    exit;
        }
	}

    /**
     * Handle running the route controller and returning the response.
     *
     * @param string $routeController The route controller to load and run.
     * @return View|Redirect A response for the framework to handle.
     * @throws \Exception Thrown when a controller action does not exist.
     */
	private function handle($routeController)
	{
	    list($controllerName, $controllerAction) = explode('@', $routeController);
		$controllerClass = '\\Kriptonic\\App\\Controllers\\' . $controllerName;

		$controller = new $controllerClass;

        if (!method_exists($controller, $controllerAction)) {
            throw new \Exception("Action '$controllerAction' does not exist on controller '$controllerClass'");
        }

        $response = $controller->$controllerAction();

		return $response;
	}

    /**
     * Register a get route.
     *
     * @param string $route The URI for this route.
     * @param string $controller The controller and action to run for the route.
     */
	public function get($route, $controller)
	{
		$this->routes['GET'][$route] = $controller;
	}

    /**
     * Register a post route.
     *
     * @param string $route The URI for this route.
     * @param string $controller The controller and action to run for the route.
     */
	public function post($route, $controller)
	{
		$this->routes['POST'][$route] = $controller;
	}
}
