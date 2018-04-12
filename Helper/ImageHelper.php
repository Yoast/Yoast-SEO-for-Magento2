<?php

namespace MaxServ\YoastSeo\Helper;

use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class ImageHelper
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * ImageHelper constructor.
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * Convert image filename into a format which the editor understands.
     *
     * @param array $item
     * @param string $type
     */
    public function updateImageDataForDataProvider(&$item, $type)
    {
        $field = "yoast_{$type}_image";
        $image = [];
        if (isset($item[$field]) && $item[$field] && is_string($item[$field])) {
            $img = $item[$field];
            $image[] = [
                'type' => 'image',
                'name' => $img,
                'url' => $this->getYoastImage($img)
            ];
        }
        if ($image) {
            $item[$field] = $image;
        } else {
            unset($item[$field]);
        }
    }

    /**
     * @param string $image
     * @return string
     */
    public function getYoastImage($image)
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'yoast/img/' . $image;
    }
}
