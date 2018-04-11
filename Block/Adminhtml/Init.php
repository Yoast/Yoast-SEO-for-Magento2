<?php

namespace MaxServ\YoastSeo\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Store\Model\ScopeInterface;

class Init extends Template
{
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
}
