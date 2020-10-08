<?php

namespace App;
use App\Database;

class App
{
    private $db;
    private $twig;

    function __construct() {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader, []);

        $this->db = new Database();
        $this->twig = $twig;
    }

    // Simple router method to each controller
    public function handle() {
        // If sub-url is empty, means it is home page, direct to home router
        if (!array_key_exists('url', $_GET)) {
            $_GET['url'] = 'home';
        }

        // $url = 'home/get'
        $url = $_GET['url'];

        // $path = [ 0 => 'home', 1 => 'get']
        $pathArr = explode('/', $url);
        // $name = 'App\Controller\Home'
        $controllerName = 'App\\Controller\\' . $pathArr[0];

        $controller = new $controllerName($this->db, $this->twig);
        if (array_key_exists(1, $pathArr) && !empty($pathArr[1])) {
            $method = $pathArr[1];
        } else {
            $method = 'main';
        }

        $result = $controller->$method();

        return $result;
    }


}
