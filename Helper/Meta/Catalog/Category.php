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

namespace MaxServ\YoastSeo\Helper\Meta\Catalog;

use MaxServ\YoastSeo\Helper\Meta;

class Category extends Meta
{

    /**
     * @var \Magento\Catalog\Model\Category
     */
    protected $category;

    /**
     * @return \Magento\Catalog\Model\Category
     */
    public function getCategory()
    {
        if (empty($this->category)) {
            $this->category = $this->registry->registry('current_category');
        }

        return $this->category;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'product.group';
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->getCategory()->getUrl();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        if (empty($this->title)) {
            $metaTitle = $this->getCategory()->getMetaTitle();

            // fallback to category name
            if (empty($metaTitle) || $metaTitle == '') {
                $metaTitle = $this->getCategory()->getName();
            }

            $this->title = $metaTitle;
        }

        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        if (empty($this->description)) {
            $metaDescription = $this->getCategory()->getMetaDescription();

            // fallback to cms block if category is configured to show cms block?

            // fallback to category content?

            $this->description = $metaDescription;
        }

        return $this->description;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        if (empty($this->image)) {
            $this->image = $this->getCategory()->getImageUrl();
        }

        return $this->image;
    }

    /**
     * @return string
     */
    public function getOpenGraphTitle()
    {
        $openGraphTitle = $this->getCategory()->getYoastFacebookTitle();

        // fallback to default title
        if (empty($openGraphTitle) || $openGraphTitle == '') {
            $openGraphTitle = $this->getTitle();
        }

        return $openGraphTitle;
    }

    /**
     * @return string
     */
    public function getOpenGraphDescription()
    {
        $openGraphDescription = $this->getCategory()->getYoastFacebookDescription();

        // fallback to default description
        if (empty($openGraphDescription) || $openGraphDescription == '') {
            $openGraphDescription = $this->getDescription();
        }

        return $openGraphDescription;
    }

    /**
     * @return string
     */
    public function getOpenGraphImage()
    {
        $openGraphImage = $this->getCategory()->getYoastFacebookImage();

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
        $twitterTitle = $this->getCategory()->getYoastTwitterTitle();

        // fallback to default title
        if (empty($twitterTitle) || $twitterTitle == '') {
            $twitterTitle = $this->getTitle();
        }

        return $twitterTitle;
    }

    /**
     * @return string
     */
    public function getTwitterDescription()
    {
        $twitterDescription = $this->getCategory()->getYoastTwitterDescription();

        // fallback to default description
        if (empty($twitterDescription) || $twitterDescription == '') {
            $twitterDescription = $this->getDescription();
        }

        return $twitterDescription;
    }

    /**
     * @return string
     */
    public function getTwitterImage()
    {
        $twitterImage = $this->getCategory()->getYoastTwitterImage();

        if ($twitterImage) {
            $twitterImage = $this->imageHelper->getYoastImage($twitterImage);
        } else {
            $twitterImage = $this->getImage();
        }

        return $twitterImage;
    }
}
