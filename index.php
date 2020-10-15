#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Jax\PropertyFinder\WebDriver\WebDriverFactory;

if (!file_exists(__DIR__ . '/config.json')) {
    echo 'config.json not found, exiting...' . PHP_EOL;
    exit(1);
}

if (!getenv('webdriver.chrome.driver')) {
    putenv(sprintf('webdriver.chrome.driver=%s', __DIR__ . '/bin/chromedriver'));
}

$config = json_decode(file_get_contents(__DIR__ . '/config.json'));

$agents = [
    'Right Move' => \Jax\PropertyFinder\Agents\RightMove::class,
    'Zoopla' => \Jax\PropertyFinder\Agents\Zoopla::class
];

$chrome = WebDriverFactory::get((object) [
    'driver' => 'Chrome',
    'os' => 'Linux'
]);

$fp = fopen(__DIR__ . '/out.csv', 'w');

fputcsv($fp, ['Agent', 'Title', 'Location', 'Price', 'Link']);

foreach ($agents as $name => $agent) {
    foreach ((new $agent($chrome, $config))->nav()->search()->getResults() as $result) {
        fputcsv($fp, [
            $name,
            $result['title'],
            $result['location'],
            $result['price'],
            $result['link']
        ]);
    }
}

fclose($fp);

$chrome->quit();


