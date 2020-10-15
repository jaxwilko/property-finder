<?php

namespace Jax\PropertyFinder\WebDriver;

use Jax\PropertyFinder\WebDriver\Contracts\WebDriverFactoryInterface;
use Facebook\WebDriver\Remote\RemoteWebDriver;

class WebDriverFactory implements WebDriverFactoryInterface
{
    /**
     * @param $browser
     * @return RemoteWebDriver
     */
    public static function get($browser): RemoteWebDriver
    {
        $namespace = sprintf('%1$s\\Providers\\%2$s\\%2$sFactory', __NAMESPACE__, $browser->driver);

        if (!class_exists($namespace)) {
            throw new \RuntimeException('unable to locate namespace: ' . $namespace);
        }

        $interfaces = class_implements($namespace);
        if ($interfaces && in_array('WebDriverFactoryInterface', $interfaces)) {
            throw new \RuntimeException('factory provided does not implement the correct interface');
        }

        return WebDriverPostInitHandler::call($browser, $namespace::get($browser));
    }
}
