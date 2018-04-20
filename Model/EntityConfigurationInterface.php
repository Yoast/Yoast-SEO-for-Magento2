<?php

namespace MaxServ\YoastSeo\Model;

use MaxServ\YoastSeo\Model\Entity\MetaProviderInterface;

interface EntityConfigurationInterface
{
    /**
     * @return array
     */
    public function toArray();

    /**
     * @return string
     */
    public function getEntityType();

    /**
     * @return string
     */
    public function getTitleField();

    /**
     * @return string
     */
    public function getSeoAttributeGroupCode();

    /**
     * @return string
     */
    public function getUrlKeyField();

    /**
     * @return string
     */
    public function getFieldWrapper();

    /**
     * @return string
     */
    public function getTemplate();

    /**
     * @return string
     */
    public function getMetaKeywordField();

    /**
     * @return MetaProviderInterface
     */
    public function getMetaProvider();
}
