<?php

namespace MaxServ\YoastSeo\Model\Entity\Configuration\Catalog;

use Magento\Catalog\Api\Data\CategoryInterface;
use MaxServ\YoastSeo\Model\Entity\Configuration\AbstractMetaProvider;

class CategoryMetaProvider extends AbstractMetaProvider
{
    /**
     * @var CategoryInterface
     */
    protected $category;

    /**
     * @return CategoryInterface
     */
    public function getCategory()
    {
        if (empty($this->category)) {
            $this->category = $this->registry->registry('current_category');
        }

        return $this->category;
    }

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'product.group';
    }

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->getFirst(
            $this->getCategory()->getMetaTitle(),
            $this->getCategory()->getName()
        );
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->getFirst(
            $this->getCategory()->getMetaDescription(),
            $this->getCategory()->getDescription()
        );
    }

    /**
     * @inheritDoc
     */
    public function getImage()
    {
        return $this->getCategory()->getImageUrl();
    }

    /**
     * @inheritDoc
     */
    public function getOpenGraphTitle()
    {
        return $this->getFirst(
            $this->getCategory()->getData('yoast_facebook_title'),
            $this->getTitle()
        );
    }

    /**
     * @inheritDoc
     */
    public function getOpenGraphDescription()
    {
        return $this->getFirst(
            $this->getCategory()->getData('yoast_facebook_description'),
            $this->getDescription()
        );
    }

    /**
     * @inheritDoc
     */
    public function getOpenGraphImage()
    {
        $image = $this->getCategory()->getData('yoast_facebook_image');
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
            $this->getCategory()->getData('yoast_twitter_title'),
            $this->getTitle()
        );
    }

    /**
     * @inheritDoc
     */
    public function getTwitterDescription()
    {
        return $this->getFirst(
            $this->getCategory()->getData('yoast_twitter_description'),
            $this->getDescription()
        );
    }

    /**
     * @inheritDoc
     */
    public function getTwitterImage()
    {
        $image = $this->getCategory()->getData('yoast_twitter_image');
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
        return -1;
    }
}
