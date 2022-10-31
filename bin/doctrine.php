#!/usr/bin/env php
<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

// replace with path to your own project bootstrap file
$container = require __DIR__ . '/../app/container.php';

// replace with mechanism to retrieve EntityManager in your app
$entityManager = $container->get(EntityManager::class);

ConsoleRunner::run(
    new SingleManagerProvider($entityManager)
);
