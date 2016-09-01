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

namespace MaxServ\YoastSeo\Helper\Analysis\Images;

use Magento\Framework\Registry;

class Category
{

    /**
     * @var Registry
     */
    protected $registry;

    public function __construct(
        Registry $registry
    ) {
        $this->registry = $registry;
    }

    public function getImages()
    {
        $images = [];

        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->registry->registry('current_category');
        $imageUrl = $category->getImageUrl();
        if ($imageUrl) {
            $images[] = sprintf("<img src='%s' alt='%s' />", $imageUrl, $category->getName());
        }

        return $images;
    }
}
