<?php

namespace App;

use App\Database;

class App
{
    private $db;
    // template engine
    private $twig;

    function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader, []);

        $this->db = new Database();
        $this->twig = $twig;
    }

    /**
     * @purpose perform a simple router method to each controller
     * @return mixed
     */
    public function handle()
    {
        // If sub-url is empty, means it is home page, direct to home router
        if (!array_key_exists('url', $_GET)) {
            $_GET['url'] = 'home';
        }

        // $url = 'home/main'
        $url = $_GET['url'];

        // $path = [ 0 => 'controller object', 1 => 'method'] eg: [home, main]
        $pathArr = explode('/', $url);

        // if the controller does not exist(path info not valid), display 404 page
        if ($pathArr[0] != 'home' && $pathArr[0] != 'file'){
            header('HTTP/1.0 404 Not Found');
            exit();
        }

        // create controller name using namespace and object name eg: $name = 'App\Controller\home'
        $controllerName = 'App\\Controller\\' . $pathArr[0];

        // create controller object using controller name, and invoke the controller method
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
