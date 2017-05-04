<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 3.0).
 * This license is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/gpl-3.0.en.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category    Maxserv: MaxServ_YoastSeo
 * @package     Maxserv: MaxServ_YoastSeo
 * @author      Vincent Hornikx <vincent.hornikx@maxserv.com>
 * @copyright   Copyright (c) 2017 MaxServ (http://www.maxserv.com)
 * @license     http://opensource.org/licenses/gpl-3.0.en.php General Public License (GPL 3.0)
 *
 */

namespace MaxServ\YoastSeo\Plugin\Catalog;

use Magento\Catalog\Model\Category;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\ScopeInterface;

class CategoryPlugin
{

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        RequestInterface $request,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->request = $request;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param Category $category
     * @return array
     */
    public function beforeSave(Category $category)
    {
        if (!$category->getId()) {
            // category has not been saved before
            $storeId = $this->request->getParam('store', 0);
            $suffix = $this->scopeConfig->getValue('head/title/suffix', ScopeInterface::SCOPE_STORE, $storeId);
            if ($suffix) {
                $metaTite = $category->getName() . ' | ' . $suffix;
                $category->setMetaTitle($metaTite);
            } else {
                $category->setMetaTitle($category->getName());
            }
        }

        return [];
    }

}
