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

namespace MaxServ\YoastSeo\Plugin\Cms\Page;

use Magento\Cms\Model\Page\DataProvider;
use MaxServ\YoastSeo\Helper\ImageHelper;

class DataProviderPlugin
{

    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    public function __construct(
        ImageHelper $imageHelper
    ) {
        $this->imageHelper = $imageHelper;
    }

    public function afterGetData(DataProvider $subject, $result)
    {
        foreach ($result as &$item) {
            $this->updateImage($item, 'facebook');
            $this->updateImage($item, 'twitter');
        }

        return $result;
    }

    protected function updateImage(&$item, $type)
    {
        $field = "yoast_{$type}_image";
        $image = [];
        if (isset($item[$field]) && $item[$field]) {
            $img = $item[$field];
            $image[] = [
                'name' => $img,
                'url' => $this->imageHelper->getYoastImage($img)
            ];
        }
        if ($image) {
            $item[$field] = $image;
        } else {
            unset($item[$field]);
        }
    }
}
