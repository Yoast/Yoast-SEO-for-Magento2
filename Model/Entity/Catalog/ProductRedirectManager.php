<?php

namespace MaxServ\YoastSeo\Model\Entity\Catalog;

use Magento\Catalog\Model\Product;
use Magento\UrlRewrite\Model\UrlRewrite;
use MaxServ\YoastSeo\Model\Config\Source\Product\RedirectDeletedOptions;
use MaxServ\YoastSeo\Model\Entity\AbstractRedirectManager;

class ProductRedirectManager extends AbstractRedirectManager
{
    const CONFIG_PATH_REDIRECT = 'yoastseo/products/redirect_delete';
    const CONFIG_PATH_CATEGORY = 'yoastseo/products/redirect_delete_category';
    const CONFIG_PATH_REDIRECT_CMS_PAGE = 'yoastseo/products/redirect_delete_cms_page';

    /**
     * @param Product $product
     */
    public function updateRedirects($product)
    {
        /** @var UrlRewrite[] $rewrites */
        $rewrites = $this->getRewrites(
            'product',
            $product->getId()
        );

        $productStoreIds = $product->getStoreIds();
        $deleteRewrites = [];
        $updateRewrites = [];

        foreach ($rewrites as $rewrite) {
            if (!in_array($rewrite->getStoreId(), $productStoreIds)) {
                $deleteRewrites[] = $rewrite;
                continue;
            }

            $rewriteTo = $this->getStoreRewrite($product, $rewrite->getStoreId());
            if (!$rewriteTo) {
                $deleteRewrites[] = $rewrite;
                continue;
            }

            $rewrite->setTargetPath($rewriteTo);
            $updateRewrites[] = $rewrite;
        }

        $this->deleteRewrites($deleteRewrites);
        $this->updateRewrites($updateRewrites);
    }

    /**
     * @param Product $product
     * @param int $storeId
     * @return string|false
     */
    protected function getStoreRewrite($product, $storeId)
    {
        if (!isset($this->configCache[$storeId]['catalog_product_rewrite'])) {
            $redirect = $this->getStoreConfig(
                self::CONFIG_PATH_REDIRECT,
                $storeId
            );
            $categoryIds = $product->getCategoryIds();
            $productCategoryId = count($categoryIds) ? $categoryIds[0] : false;
            switch (true) {
                case ($redirect === RedirectDeletedOptions::TO_PARENT_CATEGORY
                && $productCategoryId):
                    $rewrite = $this->getCategoryRewrite($productCategoryId, $storeId);
                    break;
                case ($redirect === RedirectDeletedOptions::TO_CATEGORY):
                    $categoryId = $this->getStoreConfig(
                        self::CONFIG_PATH_CATEGORY,
                        $storeId
                    );
                    $rewrite = $this->getCategoryRewrite($categoryId, $storeId);
                    break;
                case ($redirect === RedirectDeletedOptions::TO_CMS_PAGE):
                    $cmsPageId = $this->getStoreConfig(
                        self::CONFIG_PATH_REDIRECT_CMS_PAGE,
                        $storeId
                    );
                    $rewrite = $this->getCmsPageRewrite($cmsPageId, $storeId);
                    break;
                default:
                    $rewrite = false;
            }

            $this->configCache[$storeId]['catalog_product_rewrite'] = $rewrite;
        }

        return $this->configCache[$storeId]['catalog_product_rewrite'];
    }
}
