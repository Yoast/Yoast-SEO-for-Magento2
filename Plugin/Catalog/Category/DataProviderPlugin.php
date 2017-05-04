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

namespace MaxServ\YoastSeo\Plugin\Catalog\Category;

use Magento\Catalog\Model\Category\DataProvider;
use MaxServ\YoastSeo\Helper\CatalogHelper;
use MaxServ\YoastSeo\Helper\ImageHelper;

class DataProviderPlugin
{
    /**
     * @var ImageHelper
     */
    protected $imageHelper;

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
