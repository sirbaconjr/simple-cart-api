<?php

use Slim\App;

return function (App $app) {
    $app->get('/hello/{name}', function ($request, $response, $name) {
        $response->getBody()->write("Hello " . $name);

        return $response;
    });
};
