<?php

namespace MaxServ\YoastSeo\Plugin\Cms;

use Magento\Cms\Model\Page\DataProvider;
use MaxServ\YoastSeo\Helper\ImageHelper;

class PageDataProviderPlugin
{
    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * @param ImageHelper $imageHelper
     */
    public function __construct(
        ImageHelper $imageHelper
    ) {
        $this->imageHelper = $imageHelper;
    }

    /**
     * @param DataProvider $subject
     * @param array $result
     * @return array
     */
    public function afterGetData(DataProvider $subject, $result)
    {
        if (is_array($result) && count($result)) {
            foreach ($result as &$item) {
                $this->imageHelper->updateImageDataForDataProvider(
                    $item,
                    'facebook'
                );
                $this->imageHelper->updateImageDataForDataProvider(
                    $item,
                    'twitter'
                );
            }
        }

        return $result;
    }
}
