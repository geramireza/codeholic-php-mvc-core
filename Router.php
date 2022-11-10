<?php
namespace Codeholic\Phpmvc;
use App\Controllers\Controller;
use Codeholic\Phpmvc\Exceptions\NotFoundException;

class Router
{
    public array $routes;
    public Request $request;
    public Response $response;
    public function __construct(Request $request,Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get(string $path, callable|string|array  $callback)
    {
        $this->routes['get'][$path] = $callback;
    }
    public function post(string $path, callable|string|array  $callback)
    {
        $this->routes['post'][$path] = $callback;
    }
    public function resolve():string
    {
        $callback = $this->routes[$this->request->method()][$this->request->path()] ?? false;
        if($callback === false){
            $this->response->setStatusCode(404);
            throw new NotFoundException();
        }
        if(is_string($callback)){
            return  Application::$app->view->render($callback);
        }
        if(is_array($callback)){
            /**
             * @var Controller $controller
             */
            $controller = new $callback[0];
            Application::$app->controller = $controller;
            Application::$app->controller->setAction($callback[1]);
            $callback[0] = $controller;
        }
        foreach (Application::$app->controller->getMiddlewares() as $middleware){
            $middleware->execute();
        }
        return call_user_func($callback,$this->request,$this->response);
    }

}