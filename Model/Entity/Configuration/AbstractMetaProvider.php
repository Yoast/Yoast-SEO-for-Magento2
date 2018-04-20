<?php

namespace MaxServ\YoastSeo\Model\Entity\Configuration;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Layout;
use MaxServ\YoastSeo\Helper\ImageHelper;
use MaxServ\YoastSeo\Model\Entity\MetaProviderInterface;

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
     * @var Layout
     */
    protected $layout;

    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Registry $registry
     * @param UrlInterface $urlBuilder
     * @param DirectoryList $directoryList
     * @param ImageHelper $imageHelper
     */
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
     * @inheritdoc
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
     * @return string
     */
    public function getFirst()
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
