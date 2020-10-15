<?php

namespace Jax\PropertyFinder\Agents;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Jax\PropertyFinder\Agents\Contracts\AgentInterface;

class Zoopla extends Agent implements AgentInterface
{
    protected $url = 'https://www.zoopla.co.uk/';

    public function search(): AgentInterface
    {
        $this->webDriver->executeScript(sprintf(
            'document.getElementById("search-input-location").value = "%s"',
            $this->config->postcode
        ));

        $this->webDriver->executeScript('document.querySelector(".search-advanced-toggle").click()');

        $this->webDriver->executeScript(sprintf(
            'document.getElementById("radius").value = "%s"',
            $this->config->radius
        ));

        $this->webDriver->executeScript(sprintf(
            'document.getElementById("property_type").value = "%s"',
            $this->config->propertyType . 's'
        ));

        $this->webDriver->executeScript(sprintf(
            'document.getElementById("forsale_price_min").value = "%s"',
            $this->config->prices->min
        ));

        $this->webDriver->executeScript(sprintf(
            'document.getElementById("forsale_price_max").value = "%s"',
            $this->config->prices->max
        ));

        $this->webDriver->executeScript('document.querySelector("#search-submit").click()');

        $this->webDriver->wait()->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('h1'))
        );

        return $this;
    }

    public function getResults(): array
    {
        $results = [];

        do {
            $results = array_merge($results, $this->getIndexPageResults());

            $next = $this->webDriver->executeScript('
                return !!document.querySelector(".paginate a:last-child")
            ');

            if ($next) {
                $this->webDriver->executeScript('document.querySelector(".paginate a:last-child").click()');
            }

        } while ($next);

        return $results;
    }

    protected function getIndexPageResults(): array
    {
        try {
            return $this->webDriver->executeScript('
                var results = [];

                document.querySelectorAll(".listing-results-wrapper").forEach(function (result) {
                    if (result.querySelector(".price-modifier")) {
                        result.querySelector(".price-modifier").remove();
                    }
                    results.push({
                        title: result.querySelector(".listing-results-right h2 a").textContent.trim(),
                        location: result.querySelector(".listing-results-right span a").textContent.trim(),
                        price: result.querySelector(".listing-results-right a").textContent.trim(),
                        link: result.querySelector(".listing-results-right a").href
                    });
                });
                
                return results;
            ');
        } catch (\Throwable $e) {
            echo $e->getMessage() . PHP_EOL;
            return [];
        }
    }
}
