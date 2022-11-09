<?php

namespace Codeholic\Phpmvc;

class View
{
    public string $title = '';
    public function render(string $view, $params = [])
    {
        $viewContent = $this->viewContent($view, $params);
        $layoutContent = $this->layoutContent();
        return str_replace("{{content}}", $viewContent, $layoutContent);
    }

    private function layoutContent()
    {
        $layout = Application::$app->controller->getLayout();
        ob_start();
        require_once Application::$rootDir . "/resources/views/layouts/$layout.php";
        return ob_get_clean();
    }

    private function viewContent(string $view, array $params)
    {
        $view = str_replace('.', '/', $view);
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        require_once Application::$rootDir . "/resources/views/$view.php";
        return ob_get_clean();
    }

}