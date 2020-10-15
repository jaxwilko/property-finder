<?php

namespace Jax\PropertyFinder\Agents;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Jax\PropertyFinder\Agents\Contracts\AgentInterface;

class RightMove extends Agent implements AgentInterface
{
    protected $url = 'https://www.rightmove.co.uk/';

    public function search(): AgentInterface
    {
        $this->webDriver->executeScript(sprintf(
            'document.getElementById("searchLocation").value = "%s"',
            $this->config->postcode
        ));

        $this->webDriver->findElement(WebDriverBy::cssSelector('#buy'))->click();

        $this->webDriver->wait()->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('#headerTitle'))
        );

        $this->webDriver->executeScript(sprintf(
            'document.getElementById("radius").value = "%s"',
            $this->config->radius >= 1
                ? $this->config->radius . '.0'
                : $this->config->radius
        ));

        $this->webDriver->executeScript(sprintf(
            'document.getElementById("displayPropertyType").value = "%s"',
            $this->config->propertyType . 's'
        ));

        $this->webDriver->executeScript(sprintf(
            'document.getElementById("minPrice").value = "%s"',
            $this->config->prices->min
        ));

        $this->webDriver->executeScript(sprintf(
            'document.getElementById("maxPrice").value = "%s"',
            $this->config->prices->max
        ));

        $this->webDriver->findElement(WebDriverBy::cssSelector('#submit'))->click();

        return $this;
    }

    public function getResults(): array
    {
        $results = [];

        do {
            $this->webDriver->wait()->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('.propertyCard-title'))
            );

            $results = array_merge($results, $this->getIndexPageResults());

            $nextDisabled = $this->webDriver->executeScript(
                'return document.querySelector(".pagination-direction--next").hasAttribute("disabled")'
            );

            if (!$nextDisabled) {
                $this->webDriver->findElement(WebDriverBy::cssSelector('.pagination-direction--next'))->click();
            }

        } while (!$nextDisabled);

        return $results;
    }

    protected function getIndexPageResults(): array
    {
        try {
            return $this->webDriver->executeScript('
                var results = [];
    
                document.querySelectorAll(".propertyCard").forEach(function (card) {
                    results.push({
                        title: card.querySelector(".propertyCard-title").textContent.trim(),
                        location: card.querySelector(".propertyCard-address").textContent.trim(),
                        price: card.querySelector(".propertyCard-priceValue").textContent.trim(),
                        link: card.querySelector(".propertyCard-link").href
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
