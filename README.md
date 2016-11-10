# MaxServ_YoastSeo

## Introduction
This module incorporates Yoast SEO into Magento. 

## Requirements
Magento 2.1.*

## Latest version
The current version is 1.0.0. 
We are still in active development working towards a release candidate.

## Installation
Register the repository:

```
#command line
$ composer repositories.magento-module-yoastseo '{"type": "vcs", "url": "git@github.com:Yoast/magento-seo.git"}'

# OR

#composer.json
respositories: [
    ...
    {
        "type": "vcs",
        "url": "git@github.com:Yoast/magento-seo.git"
    },
    ...
]
```

Then install the module:

```
$ composer require maxserv/magento-module-yoastseo
```
