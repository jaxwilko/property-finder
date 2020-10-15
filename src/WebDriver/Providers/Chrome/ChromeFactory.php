<?php

namespace Jax\PropertyFinder\WebDriver\Providers\Chrome;

use Jax\PropertyFinder\WebDriver\Contracts\WebDriverFactoryInterface;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

class ChromeFactory implements WebDriverFactoryInterface
{
    public static function get($browser): RemoteWebDriver
    {
        if (!getenv('webdriver.chrome.driver')) {
            throw new \RuntimeException('env webdriver.chrome.driver must be defined');
        }
        return ChromeDriver::start(DesiredCapabilities::chrome()->setCapability(
            ChromeOptions::CAPABILITY,
            (new ChromeOptions())->addArguments(['headless', 'start-maximized', 'kiosk'])
        ));
    }
}
