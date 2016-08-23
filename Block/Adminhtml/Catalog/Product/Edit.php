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
 * @author      Vincent Hornikx <vincent.hornikx@maxser.com>
 * @copyright   Copyright (c) 2016 MaxServ (http://www.maxserv.com)
 * @license     http://opensource.org/licenses/gpl-3.0.en.php General Public License (GPL 3.0)
 *
 */

namespace MaxServ\YoastSeo\Block\Adminhtml\Catalog\Product;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Api\Data\ProductInterface;
use MaxServ\YoastSeo\Helper\EditProductHelper;

class Edit extends Template
{

    /**
     * @var EditProductHelper
     */
    protected $editProductHelper;

    public function __construct(
        Context $context,
        EditProductHelper $editProductHelper,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->editProductHelper = $editProductHelper;
    }

    public function getProductUrl()
    {
        /** @var Product $product */
        $product = $this->editProductHelper->getContext()->getRegistry()->registry('current_product');

        return $product->getProductUrl();
    }
}
