<?php

namespace MaxServ\YoastSeo\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Store\Model\ScopeInterface;
use MaxServ\YoastSeo\Model\EntityConfigurationPool;

/**
 * @method string getEntityType()
 * @method $this setEntityType($entityType)
 */
class Init extends Template
{
    /**
     * @var EntityConfigurationPool
     */
    protected $entityConfigurationPool;

    /**
     * @param Context $context
     * @param EntityConfigurationPool $entityConfigurationPool
     * @param array $data
     */
    public function __construct(
        Context $context,
        EntityConfigurationPool $entityConfigurationPool,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->entityConfigurationPool = $entityConfigurationPool;
    }

    /**
     * @return array
     */
    public function getEntityConfiguration()
    {
        $entityConfiguration = $this->entityConfigurationPool
            ->getEntityConfiguration($this->getEntityType());

        if (!$entityConfiguration) {
            return [];
        }

        return $entityConfiguration->toArray();
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        $storeId = $this->getRequest()->getParam('store', false);
        if (!$storeId) {
            $storeId = $this->_storeManager->getStore()->getId();
        }

        $locale = $this->_scopeConfig->getValue(
            'general/locale/code',
            ScopeInterface::SCOPE_STORES,
            $storeId
        );

        return $locale;
    }

    /**
     * @return string
     */
    public function getWysiwygUrl()
    {
        $storeId = $this->getRequest()->getParam(
            'store',
            $this->_storeManager->getDefaultStoreView()->getId()
        );
        $store = $this->_storeManager->getStore($storeId);

        return $store->getUrl('', ['_direct' => 'yoastseo/wysiwyg/render']);
    }
}
