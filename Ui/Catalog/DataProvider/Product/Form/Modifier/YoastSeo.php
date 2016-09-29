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

namespace MaxServ\YoastSeo\Ui\Catalog\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;

use Magento\Framework\Stdlib\ArrayManager;
use MaxServ\YoastSeo\Helper\ImageHelper;

class YoastSeo extends AbstractModifier
{

    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    public function __construct(
        ArrayManager $arrayManager,
        ImageHelper $imageHelper
    ) {
        $this->arrayManager = $arrayManager;
        $this->imageHelper = $imageHelper;
    }

    /**
     * @param array $data
     * @return array
     */
    public function modifyData(array $data)
    {
        foreach ($data as &$item) {
            $this->updateImageData($item['product'], 'facebook');
            $this->updateImageData($item['product'], 'twitter');
        }

        return $data;
    }

    /**
     * @param array $item
     * @param string $type
     */
    protected function updateImageData(&$item, $type)
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

    /**
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        $this->setYoastFieldClasses($meta);

        return $meta;
    }

    /**
     * @param array $meta
     */
    protected function setYoastFieldClasses(&$meta)
    {
        // general settings
        $meta['product-details']['children']['container_status']['children']['status']['arguments']['data']['config']['additionalClasses'] = 'yoastBox-productEnabledToggle';
        $meta['product-details']['children']['container_visibility']['children']['visibility']['arguments']['data']['config']['additionalClasses'] = 'yoastBox-productVisibilityToggle';
        $meta['product-details']['children']['container_name']['children']['name']['arguments']['data']['config']['additionalClasses'] = 'yoastBox-title';
        $meta['content']['children']['container_description']['children']['description']['arguments']['data']['config']['additionalClasses'] = 'yoastBox-content';
        $meta['search-engine-optimization']['arguments']['data']['config']['label'] = __('Search Engine Optimization by Yoast');
        $meta['search-engine-optimization']['children']['container_url_key']['children']['url_key']['arguments']['data']['config']['additionalClasses'] = 'yoastBox-urlKey';
        $meta['search-engine-optimization']['children']['container_meta_title']['children']['meta_title']['arguments']['data']['config']['additionalClasses'] = 'yoastBox-metaTitle hidden';
        $meta['search-engine-optimization']['children']['container_meta_description']['children']['meta_description']['arguments']['data']['config']['additionalClasses'] = 'yoastBox-metaDescription hidden';
        $meta['search-engine-optimization']['children']['container_meta_keyword']['children']['meta_keyword']['arguments']['data']['config']['additionalClasses'] = 'yoastBox-metaKeywords';
        $meta['search-engine-optimization']['children']['container_focus_keyword']['children']['focus_keyword']['arguments']['data']['config']['additionalClasses'] = 'yoastBox-focusKeyword';

        // facebook fieldset
        $meta['yoast-facebook']['arguments']['data']['config']['label'] = '';
        $meta['yoast-facebook']['arguments']['data']['config']['collapsible'] = false;
        $meta['yoast-facebook']['arguments']['data']['config']['additionalClasses'] = 'yoastBox-facebook-fieldset';
        $this->updateImageConfiguration($meta['yoast-facebook']['children']['container_yoast_facebook_image']['children']['yoast_facebook_image']['arguments']['data']['config'], 'facebook');

        // twitter fieldset
        $meta['yoast-twitter']['arguments']['data']['config']['label'] = '';
        $meta['yoast-twitter']['arguments']['data']['config']['collapsible'] = false;
        $meta['yoast-twitter']['arguments']['data']['config']['additionalClasses'] = 'yoastBox-twitter-fieldset';
        $this->updateImageConfiguration($meta['yoast-twitter']['children']['container_yoast_twitter_image']['children']['yoast_twitter_image']['arguments']['data']['config'], 'twitter');
    }

    /**
     * @param array $image
     * @param string $type
     */
    protected function updateImageConfiguration(&$image, $type)
    {
        $image = array_merge($image, [
            'additionalClasses' => 'yoast_' . $type . '_image',
            'dataType' => 'string',
            'formElement' => 'fileUploader',
            'elementTmpl' => 'ui/form/element/uploader/uploader',
            'previewTmpl' => 'Magento_Catalog/image-preview',
            'uploaderConfig' => [
                'url' => 'yoast/image/upload',
                'formData' => [
                    'yoast_image_key' => 'product[yoast_' . $type . '_image]'
                ]
            ]
        ]);
    }
}
