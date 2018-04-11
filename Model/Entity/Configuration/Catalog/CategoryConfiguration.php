<?php

namespace MaxServ\YoastSeo\Model\Entity\Configuration\Catalog;

use MaxServ\YoastSeo\Model\Entity\Configuration\AbstractEntityConfiguration;

class CategoryConfiguration extends AbstractEntityConfiguration
{
    /**
     * @inheritDoc
     */
    public function getEntityType()
    {
        return 'catalog_category';
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
        return $this->config->getCategoriesAttributeGroupCode();
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
        return '';
    }
}
