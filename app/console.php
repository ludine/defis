#!/usr/bin/env php
<?php

require '../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Genedys\Console\JsonCalculator;
use Genedys\Console\WikiCrawler;

$app = new Application();
$app->add(new JsonCalculator());
$app->add(new WikiCrawler());
$app->run();