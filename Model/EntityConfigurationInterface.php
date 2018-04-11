<?php

namespace MaxServ\YoastSeo\Model;

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
}
