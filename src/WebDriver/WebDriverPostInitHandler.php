<?php

namespace Jax\PropertyFinder\WebDriver;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverDimension;

class WebDriverPostInitHandler
{
    public static function call($browser, RemoteWebDriver $driver): RemoteWebDriver
    {
        if (in_array($browser->os, ['OS X']) || $browser->driver === 'Chrome') {
            $driver->manage()->window()->setSize(new WebDriverDimension(1920, 934));
        }

        return $driver;
    }
}
