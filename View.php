<?php
/**
 * @author kv.kn <aknk.v@protonmail.ch>
 * @product StudentLife
 * @package uglydavy\phpmvc
 */

namespace uglydavy\phpmvc;

class View
{
    public $title = '';

    public function renderContent ($viewContent)
    {
        $layoutContent = $this->layoutContent();
        return str_replace( '{{content}}', $viewContent, $layoutContent );
    }

    public function renderView ($view, $params = [])
    {
        $viewContent = $this->renderViewOnly($view, $params);
        $layoutContent = $this->layoutContent();
        return str_replace( '{{content}}', $viewContent, $layoutContent );
    }

    private function layoutContent ()
    {
        $layout =  Application::$app->layout;

        if (Application::$app->controller)
            $layout = Application::$app->controller->layout;

        ob_start();
        require_once Application::$ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();
    }

    private function renderViewOnly ($view, $params)
    {
        foreach ($params as $key => $value)
            $$key = $value;

        ob_start();
        require_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }
}