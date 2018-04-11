<?php

namespace MaxServ\YoastSeo\Model\Entity\Configuration\Cms;

use MaxServ\YoastSeo\Model\Entity\Configuration\AbstractEntityConfiguration;

class PageConfiguration extends AbstractEntityConfiguration
{
    /**
     * @inheritDoc
     */
    public function getEntityType()
    {
        return 'cms_page';
    }

    /**
     * @inheritDoc
     */
    public function getTitleField()
    {
        return 'title';
    }

    /**
     * @inheritDoc
     */
    public function getSeoAttributeGroupCode()
    {
        return 'search_engine_optimisation';
    }

    /**
     * @inheritDoc
     */
    public function getUrlKeyField()
    {
        return 'identifier';
    }

    /**
     * @inheritDoc
     */
    public function getFieldWrapper()
    {
        return '';
    }
}
