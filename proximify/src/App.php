<?php

namespace App;

/**
 * Class App
 * @package App
 */
class App
{
    private $twig;

    /**
     * App constructor.
     */
    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader, []);

        $this->twig = $twig;
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function router(array $params)
    {
        $controllerName = isset($params['queryName']) ? $params['queryName'] : '';
        $rootDir = isset($params['rootDir']) ? $params['rootDir'] : __DIR__ . '/..';

        if (!$controllerName || !strpos($controllerName, '.') === false) {
            throw new \RuntimeException('controller name error');
        }

        $prefix = 'App\\Controller\\' . $controllerName;
        $controller = new $prefix($this->twig, $rootDir);

        return $controller->run();
    }
}
