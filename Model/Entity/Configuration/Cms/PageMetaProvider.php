<?php

namespace MaxServ\YoastSeo\Model\Entity\Configuration\Cms;

use Magento\Cms\Api\Data\PageInterface;
use MaxServ\YoastSeo\Model\Entity\Configuration\AbstractMetaProvider;

class PageMetaProvider extends AbstractMetaProvider
{
    /**
     * @var PageInterface
     */
    protected $page;

    /**
     * @return PageInterface
     */
    public function getPage()
    {
        if (empty($this->page)) {
            $this->page = $this->registry->registry('current_page');
        }

        return $this->page;
    }

    /**
     * @inheritDoc
     */
    public function getType()
    {
        if ($this->urlBuilder->getCurrentUrl() === $this->urlBuilder->getBaseUrl()) {
            return 'website';
        }

        return 'article';
    }

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        $page = $this->getPage();

        return $this->getFirst(
            $page->getMetaTitle(),
            $page->getTitle(),
            $page->getContentHeading()
        );
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        $page = $this->getPage();

        return $this->getFirst(
            $page->getMetaDescription()
        );
    }

    /**
     * @inheritDoc
     */
    public function getImage()
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getOpenGraphTitle()
    {
        $page = $this->getPage();

        return $this->getFirst(
            $page->getData('yoast_facebook_title'),
            $this->getTitle()
        );
    }

    /**
     * @inheritDoc
     */
    public function getOpenGraphDescription()
    {
        $page = $this->getPage();

        return $this->getFirst(
            $page->getData('yoast_facebook_description'),
            $this->getDescription()
        );
    }

    /**
     * @inheritDoc
     */
    public function getOpenGraphImage()
    {
        $image = $this->getPage()->getData('yoast_facebook_image');
        if ($image) {
            $image = $this->imageHelper->getYoastImage($image);
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
            $this->getPage()->getData('yoast_twitter_title'),
            $this->getTitle()
        );
    }

    /**
     * @inheritDoc
     */
    public function getTwitterDescription()
    {
        return $this->getFirst(
            $this->getPage()->getData('yoast_twitter_description'),
            $this->getDescription()
        );
    }

    /**
     * @inheritDoc
     */
    public function getTwitterImage()
    {
        $image = $this->getPage()->getData('yoast_twitter_image');
        if ($image) {
            $image = $this->imageHelper->getYoastImage($image);
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
