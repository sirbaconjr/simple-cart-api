<?php

namespace Tests;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @return App
     */
    protected function getAppInstance(): App
    {
        return require __DIR__ . '/../app/app.php';
    }

    /**
     * @param string $method
     * @param string $path
     * @param array<string, string>  $headers
     * @param array<string, string> $cookies
     * @param array<string, string>  $serverParams
     * @return Request
     */
    protected function createRequest(
        string $method,
        string $path,
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $serverParams = []
    ): Request {
        $uri = new Uri('', 'localhost', 80, $path);
        $handle = fopen('php://temp', 'w+');

        if ($handle === false) {
            throw new \Error('Could not open php://temp');
        }

        $stream = (new StreamFactory())->createStreamFromResource($handle);

        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        return new SlimRequest($method, $uri, $h, $cookies, $serverParams, $stream);
    }
}
