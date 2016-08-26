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

namespace MaxServ\YoastSeo\Plugin\Cms;

use Magento\Catalog\Model\ImageUploader;
use Magento\Cms\Model\Page;

class PagePlugin
{

    /**
     * Image uploader
     *
     * @var ImageUploader
     */
    protected $imageUploader;

    public function __construct(
        ImageUploader $imageUploader
    ) {
        $this->imageUploader = $imageUploader;
    }

    /**
     * @param Page $subject
     * @return array
     */
    public function beforeSave(Page $subject)
    {
        $this->updateImage($subject, 'facebook');
        $this->updateImage($subject, 'twitter');

        return [];
    }

    /**
     * @param Page $subject
     * @param string $type
     */
    protected function updateImage($subject, $type)
    {
        $image = $subject->getData('yoast_' . $type . '_image', null);

        if (is_array($image)) {
            if (isset($image[0]['name'])) {
                $image = $image[0]['name'];
            } else {
                $image = null;
            }
            $subject->setData('yoast_' . $type . '_image', $image);
        }

        if ($image !== null) {
            try {
                $this->imageUploader->moveFileFromTmp($image);
            } catch (\Exception $e) {
                //$this->_logger->critical($e);
            }
        }
    }
}
