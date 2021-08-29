<?php

/**
 * @author kv.kn <aknk.v@protonmail.ch>
 * @product StudentLife
 * @package app\core
 */

namespace app\core;

use app\core\db\Database;
use Exception;

/**
 * @property Router router
 * @property Request request
 * @property Response response
 * @property ?Controller controller = null
 * @property Database db
 * @property Session session
 * @property ?UserModel user
 * @property string userClass
 * @property View view
 */

class Application
{
    public static $ROOT_DIR, $app;
    public $layout = 'main';

    public function __construct ($rootPath, array $config)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;

        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database( $config['db'] );
        $this->session = new Session();
        $this->userClass = $config['userClass'];
        $this->view = new View();

        $primaryValue = $this->session->get('user');

        if ($primaryValue)
        {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne( [$primaryKey => $primaryValue] );
        }
        else $this->user = null;

    }

    public static function isGuest ()
    {
        return !self::$app->user;
    }

    public function run ()
    {
        try
        {
            echo $this->router->resolve();
        }
        catch (Exception $e)
        {
            $this->response->setStatusCode( $e->getCode() );
            echo $this->view->renderView( '_error', [ 'exception' => $e ] );
        }
    }

    /**
     * @param Controller $controller
     */
    public function setController (Controller $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return Controller
     */
    public function getController (): Controller
    {
        return $this->controller;
    }

    public function login (UserModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);

        return true;
    }

    public function logout ()
    {
        $this->user = null;
        $this->session->remove('user');
    }
}