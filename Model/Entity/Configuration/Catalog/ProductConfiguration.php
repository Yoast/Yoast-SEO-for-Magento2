<?php

namespace MaxServ\YoastSeo\Model\Entity\Configuration\Catalog;

use MaxServ\YoastSeo\Model\Entity\Configuration\AbstractEntityConfiguration;
use MaxServ\YoastSeo\Model\EntityConfigurationInterface;

class ProductConfiguration extends AbstractEntityConfiguration implements EntityConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getEntityType()
    {
        return 'catalog_product';
    }

    /**
     * @inheritDoc
     */
    public function getTitleField()
    {
        return 'name';
    }

    /**
     * @inheritDoc
     */
    public function getSeoAttributeGroupCode()
    {
        return $this->config->getProductsAttributeGroupCode();
    }

    /**
     * @inheritDoc
     */
    public function getUrlKeyField()
    {
        return 'url_key';
    }

    /**
     * @inheritDoc
     */
    public function getFieldWrapper()
    {
        return 'product';
    }
}
