<?php

namespace MaxServ\YoastSeo\Model\Entity\Configuration\Catalog;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use MaxServ\YoastSeo\Helper\ImageHelper;
use MaxServ\YoastSeo\Model\Entity\Configuration\AbstractMetaProvider;

class ProductMetaProvider extends AbstractMetaProvider
{
    /**
     * @var ProductInterface
     */
    protected $product;

    /**
     * @var ImageBuilder
     */
    protected $imageBuilder;

    /**
     * @var string
     */
    protected $image;

    /**
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
        parent::__construct(
            $scopeConfig,
            $registry,
            $urlBuilder,
            $directoryList,
            $imageHelper
        );

        $this->imageBuilder = $imageBuilder;
    }

    /**
     * @return ProductInterface
     */
    public function getProduct()
    {
        if (empty($this->product)) {
            $this->product = $this->registry->registry('current_product');
        }

        return $this->product;
    }

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'product';
    }

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->getFirst(
            $this->getProduct()->getMetaTitle(),
            $this->getProduct()->getName()
        );
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->getFirst(
            $this->getProduct()->getMetaDescription(),
            $this->getProduct()->getShortDescription(),
            $this->getProduct()->getDescription()
        );
    }

    /**
     * @inheritDoc
     */
    public function getImage()
    {
        if (empty($this->image)) {
            $image = $this->imageBuilder
                ->setProduct($this->getProduct())
                ->setImageId('product_base_image')
                ->setAttributes([])
                ->create();

            $this->image = $image->getImageUrl();
        }

        return $this->image;
    }

    /**
     * @inheritDoc
     */
    public function getOpenGraphTitle()
    {
        return $this->getFirst(
            $this->getProduct()->getData('yoast_facebook_title'),
            $this->getTitle()
        );
    }

    /**
     * @inheritDoc
     */
    public function getOpenGraphDescription()
    {
        return $this->getFirst(
            $this->getProduct()->getData('yoast_facebook_description'),
            $this->getDescription()
        );
    }

    /**
     * @inheritDoc
     */
    public function getOpenGraphImage()
    {
        $image = $this->getProduct()->getData('yoast_facebook_image');
        if ($image) {
            $image = $this->imageHelper->getYoastImage($image);
        } else {
            $image = $this->getImage();
        }

        return $image;
    }

    /**
     * @inheritDoc
     */
    public function getOpenGraphVideo()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getTwitterTitle()
    {
        return $this->getFirst(
            $this->getProduct()->getData('yoast_twitter_title'),
            $this->getTitle()
        );
    }

    /**
     * @inheritDoc
     */
    public function getTwitterDescription()
    {
        return $this->getFirst(
            $this->getProduct()->getData('yoast_twitter_description'),
            $this->getDescription()
        );
    }

    /**
     * @inheritDoc
     */
    public function getTwitterImage()
    {
        $image = $this->getProduct()->getData('yoast_twitter_image');
        if ($image) {
            $image = $this->imageHelper->getYoastImage($image);
        } else {
            $image = $this->getImage();
        }

        return $image;
    }

    /**
     * @inheritDoc
     */
    public function getPrice()
    {
        return $this->getProduct()->getPrice();
    }
}
