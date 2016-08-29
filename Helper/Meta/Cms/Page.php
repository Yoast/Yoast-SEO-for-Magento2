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

namespace MaxServ\YoastSeo\Helper\Meta\Cms;

use MaxServ\YoastSeo\Helper\Meta;

class Page extends Meta
{

    /**
     * @var \Magento\Cms\Model\Page
     */
    protected $page;

    /**
     * @return \Magento\Cms\Model\Page|mixed
     */
    public function getPage()
    {
        if (empty($this->page)) {
            $this->page = $this->registry->registry('current_page');
        }

        return $this->page;
    }

    /**
     * @return string
     */
    public function getType()
    {
        if ($this->_urlBuilder->getCurrentUrl() === $this->_urlBuilder->getBaseUrl()) {
            return 'website';
        }
        return 'article';
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_urlBuilder->getUrl(null, ['_direct' => $this->getPage()->getIdentifier()]);
    }

    /**
     * @return null|string
     */
    public function getTitle()
    {
        if (empty($this->title)) {
            $metaTitle = $this->getPage()->getMetaTitle();

            // fallback to page title
            if (empty ($metaTitle) || $metaTitle == '') {
                $metaTitle = $this->getPage()->getTitle();
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
            $metaDescription = $this->getPage()->getMetaDescription();

            // fallback to page content ?

            $this->description = $metaDescription;
        }

        return $this->description;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return '';
    }

    /**
     * @return null|string
     */
    public function getOpenGraphTitle()
    {
        $openGraphTitle = $this->getPage()->getYoastFacebookTitle();

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
        $openGraphDescription = $this->getPage()->getYoastFacebookDescription();

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
        $openGraphImage = $this->getPage()->getYoastFacebookImage();

        if ($openGraphImage) {
            $openGraphImage = $this->imageHelper->getYoastImage($openGraphImage);
        }

        return $openGraphImage;
    }

    /**
     * @return null|string
     */
    public function getTwitterTitle()
    {
        $twitterTitle = $this->getPage()->getYoastTwitterTitle();

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
        $twitterDescription = $this->getPage()->getYoastTwitterDescription();

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
        $twitterImage = $this->getPage()->getYoastTwitterImage();

        if ($twitterImage) {
            $twitterImage = $this->imageHelper->getYoastImage($twitterImage);
        }

        return $twitterImage;
    }
}
