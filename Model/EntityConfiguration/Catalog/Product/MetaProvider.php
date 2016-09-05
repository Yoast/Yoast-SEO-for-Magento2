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

namespace MaxServ\YoastSeo\Model\EntityConfiguration\Catalog\Product;

use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use MaxServ\YoastSeo\Helper\ImageHelper;
use MaxServ\YoastSeo\Model\EntityConfiguration\AbstractMetaProvider;

class MetaProvider extends AbstractMetaProvider
{

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $product;

    /**
     * @var ImageBuilder
     */
    protected $imageBuilder;

    /**
     * MetaProvider constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Registry $registry
     * @param UrlInterface $urlBuilder
     * @param DirectoryList $directoryList
     * @param ImageHelper $imageHelper
     * @param ImageBuilder $imageBuilder
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Registry $registry,
        UrlInterface $urlBuilder,
        DirectoryList $directoryList,
        ImageHelper $imageHelper,
        ImageBuilder $imageBuilder
    ) {
        parent::__construct($scopeConfig, $registry, $urlBuilder, $directoryList, $imageHelper);
        $this->imageBuilder = $imageBuilder;
    }

    /**
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        if (empty($this->product)) {
            $this->product = $this->registry->registry('current_product');
        }

        return $this->product;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'product';
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->getProduct()->getProductUrl();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        if (empty($this->title)) {
            $this->title = $this->getFirstAvailableValue(
                $this->getProduct()->getMetaTitle(),
                $this->getProduct()->getName()
            );
        }
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        if (empty($this->description)) {

            $this->description = $this->getFirstAvailableValue(
                $this->getProduct()->getMetaDescription(),
                $this->getProduct()->getShortDescription(),
                $this->getProduct()->getDescription()
            );
        }

        return $this->description;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        if (empty($this->image)) {
            $image =  $this->imageBuilder->setProduct($this->getProduct())
                ->setImageId('product_base_image')
                ->setAttributes([])
                ->create();

            $this->image = $image->getImageUrl();
        }

        return $this->image;
    }

    public function getPrice()
    {

    }

    /**
     * @return string
     */
    public function getOpenGraphTitle()
    {
        return $this->getFirstAvailableValue(
            $this->getProduct()->getYoastFacebookTitle(),
            $this->getTitle()
        );
    }

    /**
     * @return string
     */
    public function getOpenGraphDescription()
    {
        return $this->getFirstAvailableValue(
            $this->getProduct()->getYoastFacebookDescription(),
            $this->getDescription()
        );
    }

    /**
     * @return string
     */
    public function getOpenGraphImage()
    {
        $openGraphImage = $this->getProduct()->getYoastFacebookImage();

        if ($openGraphImage) {
            $openGraphImage = $this->imageHelper->getYoastImage($openGraphImage);
        } else {
            $openGraphImage = $this->getImage();
        }

        return $openGraphImage;
    }

    /**
     * @return string
     */
    public function getTwitterTitle()
    {
        return $this->getFirstAvailableValue(
            $this->getProduct()->getYoastTwitterTitle(),
            $this->getTitle()
        );
    }

    /**
     * @return string
     */
    public function getTwitterDescription()
    {
        return $this->getFirstAvailableValue(
            $this->getProduct()->getYoastTwitterDescription(),
            $this->getDescription()
        );
    }

    /**
     * @return string
     */
    public function getTwitterImage()
    {
        $twitterImage = $this->getProduct()->getYoastTwitterImage();

        if ($twitterImage) {
            $twitterImage = $this->imageHelper->getYoastImage($twitterImage);
        } else {
            $twitterImage = $this->getImage();
        }

        return $twitterImage;
    }
}
