<?php

namespace Jax\PropertyFinder\Agents;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Jax\PropertyFinder\Agents\Contracts\AgentInterface;

abstract class Agent implements AgentInterface
{
    protected $webDriver;

    protected $config;

    protected $url;

    public function __construct(RemoteWebDriver $webDriver, \stdClass $config)
    {
        $this->webDriver = $webDriver;
        $this->config = $config;
    }

    public function nav(): AgentInterface
    {
        $this->webDriver->get($this->url);

        return $this;
    }
}
