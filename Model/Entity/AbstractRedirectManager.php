<?php

namespace MaxServ\YoastSeo\Model\Entity;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Url as UrlBuilder;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollection;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory;
use Magento\UrlRewrite\Model\UrlRewrite;

class AbstractRedirectManager
{
    /**
     * @var UrlRewriteCollectionFactory
     */
    protected $rewriteCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var array
     */
    protected $configCache = [];

    /**
     * @param UrlRewriteCollectionFactory $rewriteCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        UrlRewriteCollectionFactory $rewriteCollectionFactory,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        UrlBuilder $urlBuilder
    ) {
        $this->rewriteCollectionFactory = $rewriteCollectionFactory;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param string $configPath
     * @param int $storeId
     * @return mixed
     */
    protected function getStoreConfig($configPath, $storeId)
    {
        if (!isset($this->configCache[$storeId][$configPath])) {
            $value = $this->scopeConfig->getValue(
                $configPath,
                ScopeInterface::SCOPE_STORES,
                $storeId
            );
            $this->configCache[$storeId][$configPath] = $value;
        }

        return $this->configCache[$storeId][$configPath];
    }

    /**
     * @param string $entityType
     * @param int $entityId
     * @return array
     */
    protected function getRewrites($entityType, $entityId)
    {
        /** @var UrlRewriteCollection $collection */
        $collection = $this->rewriteCollectionFactory->create();
        $collection
            ->addFieldToFilter('entity_type', ['eq' => $entityType])
            ->addFieldToFilter('entity_id', ['eq' => $entityId]);

        return $collection->getItems();
    }

    /**
     * @param UrlRewrite[] $rewrites
     */
    protected function updateRewrites($rewrites)
    {
        foreach ($rewrites as $rewrite) {
            try {
                $rewrite->setRedirectType(301);
                $rewrite->save();
            } catch (\Exception $e) {
                // todo: fix
            }
        }
    }

    /**
     * @param UrlRewrite[] $rewrites
     */
    protected function deleteRewrites($rewrites)
    {
        foreach ($rewrites as $rewrite) {
            try {
                $rewrite->delete();
            } catch (\Exception $e) {
                // todo: fix
            }
        }
    }

    /**
     * @param string $route
     * @param string $entityType
     * @param int $storeId
     * @return string|false
     */
    protected function getEntityUrl($route, $entityType, $storeId)
    {
        try {
            /** @var UrlRewriteCollection $collection */
            $collection = $this->rewriteCollectionFactory->create();
            $collection
                ->addFieldToFilter('target_path', ['eq' => $route])
                ->addFieldToFilter('entity_type', ['eq' => $entityType])
                ->addFieldToFilter('store_id', ['eq' => $storeId]);

            $url = false;
            /** @var UrlRewrite $rewrite */
            $rewrite = $collection->getFirstItem();
            if ($rewrite) {
                $url = $rewrite->getRequestPath();
            }
        } catch (\Exception $e) {
            $url = false;
        }

        return $url;
    }

    /**
     * @param int $categoryId
     * @param int $storeId
     * @return string
     */
    protected function getCategoryRewrite($categoryId, $storeId)
    {
        return $this->getEntityUrl(
            'catalog/category/view/id/' . $categoryId,
            'category',
            $storeId
        );
    }

    /**
     * @param $pageId
     * @param $storeId
     * @return string
     */
    protected function getCmsPageRewrite($pageId, $storeId)
    {
        return $this->getEntityUrl(
            'cms/page/view/page_id/' . $pageId,
            'cms-page',
            $pageId,
            $storeId
        );
    }
}
