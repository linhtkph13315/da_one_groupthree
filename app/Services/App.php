<?php

namespace app\Services;

use app\Http\Controllers\Auth\AuthController;
use app\Http\Controllers\Admin\OptionController;
use storage\Session;
use app\Helpers\Helper;

class App
{
    public Session $session;
    public Request $request;
    public Route $route;
    public Template $template;
    public AuthController $auth;
    public Helper $helper;
    public OptionController $option;
    public static App $app;

    public function __construct()
    {
        $this->session = new Session();
        $this->template = new Template(VIEW_DIR);
        $this->request = new Request();
        $this->helper = new Helper();
        $this->auth = new AuthController();
        $this->option = new OptionController();
        $this->route = new Route($this->request);
        self::$app = $this;
    }

    public function run()
    {
        $this->route->map();
    }
}