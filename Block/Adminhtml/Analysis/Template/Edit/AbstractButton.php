<?php

namespace MaxServ\YoastSeo\Block\Adminhtml\Analysis\Template\Edit;

use Magento\Backend\Block\Widget\Context;
use MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface;

class AbstractButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        $this->context = $context;
    }

    /**
     * @return int
     */
    public function getTemplateId()
    {
        return (int)$this->context->getRequest()->getParam(
            AnalysisTemplateInterface::KEY_TEMPLATE_ID
        );
    }

    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route, $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
