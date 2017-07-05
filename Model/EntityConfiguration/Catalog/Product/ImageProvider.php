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

namespace MaxServ\YoastSeo\Model\EntityConfiguration\Catalog\Product;

use Magento\Catalog\Helper\Image;
use Magento\Framework\Registry;
use MaxServ\YoastSeo\Model\EntityConfiguration\ImageProviderInterface;

class ImageProvider implements ImageProviderInterface
{

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Image
     */
    protected $image;

    public function __construct(
        Registry $registry,
        Image $image
    ) {
        $this->registry = $registry;
        $this->image = $image;
    }

    public function getImages()
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->registry->registry('current_product');
        $gallery = $product->getMediaGalleryImages();
        $images = [];

        if ($gallery && $gallery->count()) {
            foreach ($gallery as $image) {
                $images[] = sprintf("<img src='%s' alt='%s' />", $image->getUrl(), $product->getName());
            }
        }

        return $images;
    }
}
