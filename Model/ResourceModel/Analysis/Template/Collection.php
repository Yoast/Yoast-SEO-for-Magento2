<?php

namespace MaxServ\YoastSeo\Model\ResourceModel\Analysis\Template;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface;
use MaxServ\YoastSeo\Model\Analysis\Template;
use MaxServ\YoastSeo\Model\ResourceModel\Analysis\Template as TemplateResource;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = AnalysisTemplateInterface::KEY_TEMPLATE_ID; // phpcs:ignore
    
    /**
     * @inheritDoc
     */
    protected function _construct() // phpcs:ignore
    {
        $this->_init(
            Template::class,
            TemplateResource::class
        );
    }
}
