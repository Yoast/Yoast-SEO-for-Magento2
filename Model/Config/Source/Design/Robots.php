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
 * @author      Luk van den Borne <luk.van.den.borne@maxserv.com>
 * @copyright   Copyright (c) 2017 MaxServ (http://www.maxserv.com)
 * @license     http://opensource.org/licenses/gpl-3.0.en.php General Public License (GPL 3.0)
 *
 */
namespace MaxServ\YoastSeo\Model\Config\Source\Design;

use Magento\Config\Model\Config\Source\Design\Robots as RobotsParent;
use Magento\Framework\Option\ArrayInterface;

class Robots extends RobotsParent implements ArrayInterface
{
     /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return array_merge(
            [
                ['value' => '', 'label' => __('Use system value')],
            ],
            parent::toOptionArray()
        );
    }
}
