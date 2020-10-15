# Property Finder

### Installation

```shell script
git clone git@github.com:jaxwilko/property-finder.git
cd property-finder
cp config.json.example config.json
```

Enter your search terms into `config.json`.

> You will need to get a [chrome webdriver](https://chromedriver.chromium.org/downloads) if you don't already have one.

Either drop the chromedriver binary into the bin folder included with this repo or set an `env` value of 
`webdriver.chrome.driver` to the path of your chromedriver binary.

### Execution

```shell script
php index.php
```

This will output `out.csv` in the project directory.
