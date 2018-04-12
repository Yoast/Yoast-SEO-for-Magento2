<?php

namespace MaxServ\YoastSeo\Plugin\Catalog;

use Magento\Catalog\Model\Category\DataProvider;
use MaxServ\YoastSeo\Helper\CatalogHelper;
use MaxServ\YoastSeo\Helper\ImageHelper;

class CategoryDataProviderPlugin
{
    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * @var CatalogHelper
     */
    protected $catalogHelper;

    /**
     * @param ImageHelper $imageHelper
     * @param CatalogHelper $catalogHelper
     */
    public function __construct(
        ImageHelper $imageHelper,
        CatalogHelper $catalogHelper
    ) {
        $this->imageHelper = $imageHelper;
        $this->catalogHelper = $catalogHelper;
    }

    /**
     * @param DataProvider $dataProvider
     * @param $result
     * @return mixed
     */
    public function afterPrepareMeta(DataProvider $dataProvider, $result)
    {
        if ($this->catalogHelper->isRootCategory()) {
            $result['search_engine_optimization']['arguments']['data']['config']['componentDisabled'] = true;
        }

        return $result;
    }

    /**
     * @param DataProvider $subject
     * @param array $result
     * @return array
     */
    public function afterGetData(DataProvider $subject, $result)
    {
        foreach ($result as &$item) {
            $this->imageHelper->updateImageDataForDataProvider($item, 'facebook');
            $this->imageHelper->updateImageDataForDataProvider($item, 'twitter');
        }

        return $result;
    }
}
