# Yoast SEO for Magento 2 (by MaxServ)

- [Introduction](##introduction)
- [Features](##features)
- [Requirements](##requirements)
- [Installation](##installation)
- [Configuration](##configuration)

## Introduction
We have developed this advanced SEO module in close cooperation with YOAST inc. 
It incorporates the YOAST library into Magento2.

## Requirements
Magento 2.2.* OpenSource. 
This version of the module has not (yet) been tested on Magento 2 Commerce or Magento 2 Cloud editions.

## Features
### Proper meta tags
- ld+json schema data which identifies the page
    - Home page: [Company](https://schema.org/Corporation) and [Website](https://schema.org/WebSite)
    - Product pages: [Product](https://schema.org/Product)
    - SiteLink Search Box [read more](https://developers.google.com/search/docs/guides/enhance-site#add-a-sitelinks-searchbox-for-your-site)
- canonical url tag
- OpenGraph data
- Facebook configuration data (app id, admin ids)
- Twitter card data

### Live SEO analysis from within the edit forms
When you open an edit form, you'll notice that the default 'Search Engine Optimisation' section has been changed to 'YoastSEO'.
Inside this section we have added a live analysis tool. 
This tool runs a whole battery of tests on your content and shows you the results.
Results marked with a green status light are OK, 
orange means you might want to have a look, 
while red indicates a problem that you should try to fix immediately.

You'll find the analyis tool in the edit forms of these entities:
- Categories
- Products
- CMS Pages

### Customize analysis templates
If you have custom attributes which contain content, we've got you covered.
You can edit the analysis templates to include you own custom attributes.
Read more on how to configure analysis templates [here](##analysis-templates).

### Create redirects for deleted products
Normally a product URL will generate a 404 page after you delete the product.
Now you can configure what should happen yourself. 
Choose between redirecting to the product's category page, 
a fixed category page or a CMS page of your choice.
Or, if you want, you can choose not to create redirects.

## Installation
This module can be installed using composer. 

### Packagist
If you have access to packagist (and by default you do), all you need to do is run this require statement.
```bash
composer require maxserv/magento-module-yoastseo
```

### GitHub
If you do not have access to packagist, 
you need to add our GitHub repository to your composer configuration before you can require this module:
```bash
composer config repositories.yoastseo git https://github.com/Yoast/Yoast-SEO-for-Magento2
composer require maxserv/magento-module-yoastseo
```

### Download
If for some reason you can't use composer then please follow these steps:
1. Browse to the releases section on our [GitHub page](https://github.com/Yoast/magento-seo/releases)
2. Download the latest release in zip or tar format
3. Create a directory in your project: ```app/code/MaxServ/YoastSeo```
4. Unpack the package into that directory

## Configuration
You can find the configuration by navigating to Stores -> Configuration in the main menu. 
In the configuration sections select 'YoastSEO' and then click on 'SEO Settings'.

##Analysis templates
You can find the analysis templates by navigating to Marketing -> YoastSEO -> Analysis Templates using the main menu.

### Template format
The template consists of plain HTML and placeholders for attributes. 
Placeholders are defined with double handlebars:
```
{{<attribute_code> reader='<reader>'[ provider='<provider>'][ default='<default>']}}
```

|Part|Possible values|
|---|---|
|```attribute_code```|Any valid attribute code (with some exceptions)|
|```reader```|```text```,```wysiwyg```,```cms_block```,```category_landing_page```|
|```provider```|```product_images```|
|```default```|Any value|

### Readers
#### Text
The text reader returns the literal value of the input field component. 
The value will not be processed.

#### Wysiwyg
The value of the input field component will be rendered using frontend rendering.
This ensures that any placeholders (e.g. widget placeholders) in the content will
be expanded before analysis.

#### CMS Block
The value of the the input field component should be the numerical identifier of a CMS block.
If it is, the CMS block will be rendered using frontend rendering.

#### Category Landing Page
Can only be used in category forms,
and will only render anything if the category display mode has been set to either 
'Static block only' or 'Static block and products'.
If this is the case, this reader will pull content from the 'Add CMS Block' field in the 'Content' section of the category form. 

### Providers
Use a provider in conjunction with a non-existent attribute code to provide data to the template processor
which can not be read from other form fields. A provider should return plain HTML which requires no more processing. 

#### Product Images 
The product images provider reads the product media gallery and renders the images as ```<img />``` tags.

### Template processing 
For each placeholder, the template processor will try to retrieve content based on the placeholder configuration. 
The following steps are treated in an ```if, else if, else if, else``` manner

- If a form input component can be found based on the attribute code, **and** a reader has been defined, 
the template processor will instruct the reader to process the input component's value.
- If a form input component can be found based on the attribute code, the input component's value is returned.
- If a form input component could exist but doesn't exist yet (JIT fieldset rendering), 
**and** the entity already had a value for that attribute code, then that value is returned.
- If a provider has been configured, the provider is instructed to return a value.
- If the placeholder has a default value configured, the default value is returned.
- A blank value is returned.
