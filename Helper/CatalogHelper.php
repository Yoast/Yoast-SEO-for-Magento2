<?php

namespace MaxServ\YoastSeo\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;

class CatalogHelper extends AbstractHelper
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);

        $this->storeManager = $storeManager;
    }

    /**
     * @return bool
     */
    public function isRootCategory()
    {
        $categoryId = $this->_request->getParam('id');
        if (!$categoryId) {
            return true;
        }
        $isRootCategory = false;
        $stores = $this->storeManager->getStores();
        foreach ($stores as $store) {
            if ((int)$store->getRootCategoryId() === (int)$categoryId) {
                $isRootCategory = true;
                break;
            }
        }

        return $isRootCategory;
    }
}
