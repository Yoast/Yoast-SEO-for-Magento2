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

namespace MaxServ\YoastSeo\Model\EntityConfiguration;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Layout;
use MaxServ\YoastSeo\Helper\ImageHelper;

abstract class AbstractMetaProvider implements MetaProviderInterface
{

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * @var Layout
     */
    protected $layout;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $image;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Registry $registry,
        UrlInterface $urlBuilder,
        DirectoryList $directoryList,
        ImageHelper $imageHelper
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->registry = $registry;
        $this->urlBuilder = $urlBuilder;
        $this->directoryList = $directoryList;
        $this->imageHelper = $imageHelper;
    }

    /**
     * @return string
     */
    public function getStoreName()
    {
        return $this->scopeConfig->getValue('general/store_information/name', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getTitleTemplate()
    {
        return '%s | ' . $this->getStoreName();
    }

    /**
     * @param string $imageUrl
     * @return array
     */
    public function getImageMeta($imageUrl)
    {
        $baseUrl = $this->urlBuilder->getBaseUrl();
        $root = $this->directoryList->getRoot() . '/';
        $image = str_replace($baseUrl, $root, $imageUrl);

        $meta = [];

        if (file_exists($image)) {
            $size = getimagesize($image);

            $meta = [
                'width' => $size[0],
                'height' => $size[1],
                'mime' => $size['mime']
            ];
        }

        return $meta;
    }

    /**
     * @return null|string
     */
    public function getFirstAvailableValue()
    {
        $args = func_get_args();
        $result = null;
        foreach ($args as $arg) {
            if (!empty($arg) && is_string($arg) && $arg !== '') {
                $result = $arg;
                break;
            }
        }

        return $result;
    }

    /**
     * @param Layout $layout
     * @return $this
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * @return Layout
     */
    public function getLayout()
    {
        return $this->layout;
    }
}
