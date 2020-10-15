<?php

namespace Jax\PropertyFinder\Agents\Contracts;

use Facebook\WebDriver\Remote\RemoteWebDriver;

interface AgentInterface
{
    public function __construct(RemoteWebDriver $webDriver, \stdClass $config);

    public function nav(): AgentInterface;

    public function search(): AgentInterface;

    public function getResults(): array;
}
