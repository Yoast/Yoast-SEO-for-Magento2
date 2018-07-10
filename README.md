# MaxServ_YoastSeo

## Introduction
We have developed this advanced SEO module in close cooperation with YOAST inc. 
It incorporates the YOAST library into Magento2.

## Requirements
Magento 2.2.* OpenSource. This version of the module has not yet been tested on Magento 2 Commerce, use at own risk!

## Installation
This module can be installed using composer. 

#### Packagist
```bash
composer require maxserv/magento-module-yoastseo
```

#### GitHub
If you do not have access to packagist, you need to add our GitHub repository to your composer configuration:
```bash
composer config repositories.yoastseo git https://github.com/Yoast/Yoast-SEO-for-Magento2
```

#### Download
If for some reason you can't use composer then please follow these steps:
1. Browse to the releases section on our [GitHub page](https://github.com/Yoast/magento-seo/releases)
2. Download the latest release
3. Create a directory in your project: ```app/code/MaxServ/YoastSeo```
4. Unpack the release into that directory
