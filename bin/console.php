<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;

$container = require __DIR__ . '/../app/container.php';

$env = (new ArgvInput())->getParameterOption(['--env', '-e'], 'dev');

if ($env) {
    $_ENV['APP_ENV'] = $env;
}

try {
    /** @var Application $application */
    $application = $container->get(Application::class);
    exit($application->run());
} catch (Throwable $exception) {
    echo $exception->getMessage();
    exit(1);
}
