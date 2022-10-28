<?php

namespace Tests;

use Slim\App;

class HelloNameTest extends TestCase
{
    private App $app;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = $this->getAppInstance();
    }

    public function testHelloEndpoint()
    {
        $request = $this->createRequest('GET', '/hello/Test');

        $response = $this->app->handle($request);
        $body = (string) $response->getBody();

        $this->assertEquals("Hello Test", $body);
    }
}
