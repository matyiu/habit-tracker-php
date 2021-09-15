<?php

namespace App;

class Router {
    // HTTP METHODS
    private const GET_METHOD = 'GET';
    private const POST_METHOD = 'POST';
    private const PUT_METHOD = 'PUT';
    private const DELETE_METHOD = 'DELETE';

    private array $handlers;

    public function get(string $path, $handler)
    {
        $this->addHandler(self::GET_METHOD, $path, $handler);
    }

    public function post(string $path, $handler)
    {
        $this->addHandler(self::POST_METHOD, $path, $handler);
    }

    public function put(string $path, $handler)
    {
        $this->addHandler(self::PUT_METHOD, $path, $handler);
    }

    public function delete(string $path, $handler)
    {
        $this->addHandler(self::DELETE_METHOD, $path, $handler);
    }

    private function addHandler(string $method, string $path, $handler)
    {
        $this->handlers[$method . ' ' . $path] = [
            'path' => $path,
            'method' => $method,
            'callback' => $handler,
        ];
    }

    private function returnMissingEndpoint()
    {
        http_response_code(404);

        echo "Error 404";
    }

    public function run()
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $httpPath = trim($_SERVER['REQUEST_URI'], '/');

        $callback = null;
        foreach ($this->handlers as $handler) {
            if ($handler['path'] === $httpPath && $handler['method'] === $httpMethod) {
                $callback = $handler['callback'];
            }
        }

        if (!$callback) {
            $this->returnMissingEndpoint();

            return;
        }

        $rawRequestBody = json_decode(file_get_contents('php://input'), true) ?? [];
        $requestData = array_merge($rawRequestBody, $_POST, $_GET);
        if (is_array($callback)) {
            $class = new $callback[0];
            $method = $callback[1];
            $results = call_user_func_array([$class, $method], [$requestData]);
        } else if (is_callable($callback)) {
            $results = call_user_func_array($callback, [$requestData]);
        }

        echo $results;
    }
}