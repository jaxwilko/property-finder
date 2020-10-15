<?php

namespace Jax\PropertyFinder\WebDriver\Contracts;

use Facebook\WebDriver\Remote\RemoteWebDriver;

interface WebDriverFactoryInterface
{
    public static function get($browser): RemoteWebDriver;
}
