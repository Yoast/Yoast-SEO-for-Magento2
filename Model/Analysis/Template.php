<?php

namespace MaxServ\YoastSeo\Model\Analysis;

use Magento\Framework\Model\AbstractModel;
use MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface;
use MaxServ\YoastSeo\Model\ResourceModel\Analysis\Template as TemplateResource;

class Template extends AbstractModel implements AnalysisTemplateInterface
{
    /**
     * @inheritDoc
     */
    protected function _construct() // phpcs:ignore
    {
        $this->_init(TemplateResource::class);
    }

    /**
     * @inheritDoc
     */
    public function getTemplateId()
    {
        return $this->getId();
    }

    /**
     * @inheritDoc
     */
    public function setTemplateId($templateId)
    {
        return $this->setId($templateId);
    }

    /**
     * @inheritDoc
     */
    public function getEntityType()
    {
        return $this->getData(self::KEY_ENTITY_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setEntityType($entityType)
    {
        return $this->setData(self::KEY_ENTITY_TYPE, $entityType);
    }

    /**
     * @inheritDoc
     */
    public function getContent()
    {
        return $this->getData(self::KEY_CONTENT);
    }

    /**
     * @inheritDoc
     */
    public function setContent($content)
    {
        return $this->setData(self::KEY_CONTENT, $content);
    }
}
