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

namespace MaxServ\YoastSeo\Model\EntityConfiguration\Cms\Page;

use MaxServ\YoastSeo\Model\EntityConfiguration\AbstractMetaProvider;

class MetaProvider extends AbstractMetaProvider
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
        if ($this->urlBuilder->getCurrentUrl() === $this->urlBuilder->getBaseUrl()) {
            return 'website';
        }
        return 'article';
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->urlBuilder->getUrl(null, ['_direct' => $this->getPage()->getIdentifier()]);
    }

    /**
     * @return null|string
     */
    public function getTitle()
    {
        if (empty($this->title)) {
            $this->title = $this->getFirstAvailableValue(
                $this->getPage()->getMetaTitle(),
                $this->getPage()->getTitle(),
                $this->getPage()->getContentHeading()
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
                $this->getPage()->getMetaDescription()
            // fallback to page content ?
            );
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
        return $this->getFirstAvailableValue(
            $this->getPage()->getYoastFacebookTitle(),
            $this->getTitle()
        );
    }

    /**
     * @return string
     */
    public function getOpenGraphDescription()
    {
        return $this->getFirstAvailableValue(
            $this->getPage()->getYoastFacebookDescription(),
            $this->getDescription()
        );
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
        return $this->getFirstAvailableValue(
            $this->getPage()->getYoastTwitterTitle(),
            $this->getTitle()
        );
    }

    /**
     * @return string
     */
    public function getTwitterDescription()
    {
        return $this->getFirstAvailableValue(
            $this->getPage()->getYoastTwitterDescription(),
            $this->getDescription()
        );
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
