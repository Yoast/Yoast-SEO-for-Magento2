<?php

namespace MaxServ\YoastSeo\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    /** @var string */
    const XML_PATH_PRODUCT_ATTRIBUTE_GROUP_CODE = 'yoastseo/products/attribute_group_code';
    const XML_PATH_CATEGORY_ATTRIBUTE_GROUP_CODE = 'yoastseo/categories/attribute_group_code';

    /**
     * @return string
     */
    public function getProductsAttributeGroupCode()
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_PRODUCT_ATTRIBUTE_GROUP_CODE,
            ScopeInterface::SCOPE_STORES
        );
    }

    /**
     * @return string
     */
    public function getCategoriesAttributeGroupCode()
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_CATEGORY_ATTRIBUTE_GROUP_CODE,
            ScopeInterface::SCOPE_STORES
        );
    }
}
