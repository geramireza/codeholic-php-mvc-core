<?php

namespace Codeholic\Phpmvc;

use App\Controllers\Controller;

class Application
{
    public const EVENT_BEFORE_REQUEST = 'beforeRequest';
    public const EVENT_AFTER_REQUEST = 'afterRequest';
    public array $eventListeners = [];

    public Router $router;
    public Request $request;
    public Response $response;
    public static Application $app;
    public static string $rootDir;
    public Controller $controller;
    public Database $database;
    public Migration $migration;
    public Session $session;
    public string $userClass;
    public ?Model $user = null;
    public View $view;

    public function __construct(string $rootDir, array $appConfig)
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->controller = new Controller();
        self::$app = $this;
        self::$rootDir = $rootDir;
        $this->router = new Router($this->request, $this->response);
        $this->database = Database::getInstance();
        $this->migration = new Migration();
        $this->session = new Session();
        $this->userClass = $appConfig['userClass'];
        $this->view = new View();

        $primaryValue = $this->session->get('user');
        if ($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::first($primaryKey, $primaryValue);
        }
    }

    public function run()
    {
        $this->trigerEvent(Application::EVENT_BEFORE_REQUEST);
        try {
            echo $this->router->resolve();
        } catch (\Exception $exception) {
            echo $this->view->render('errors',['exception' => $exception]);
        }
    }

    public function login(Model $user)
    {
        $this->user = $user;
        $primaryKey = $this->userClass::primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        $this->session->setFlash('success', 'You are logged in');
        return true;
    }

    public static function isGuest()
    {
        return !self::$app->user;
    }
    public static function isAdmin(){
        return self::$app->user?->type === 'admin';
    }
    public static function logout()
    {
        self::$app->session->forget('user');
        self::$app->response->redirect('/');
    }

    public function trigerEvent($event)
    {
        $callbacks = $this->eventListeners[$event] ?? [];
        foreach ($callbacks as $callback){
            call_user_func($callback);
        }
    }
    public function on($event,$callback){
        $this->eventListeners[$event][] = $callback;
    }

}