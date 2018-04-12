<?php

namespace MaxServ\YoastSeo\Ui\Catalog\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use MaxServ\YoastSeo\Helper\Config;
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

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param ArrayManager $arrayManager
     * @param ImageHelper $imageHelper
     * @param Config $config
     */
    public function __construct(
        ArrayManager $arrayManager,
        ImageHelper $imageHelper,
        Config $config
    ) {
        $this->arrayManager = $arrayManager;
        $this->imageHelper = $imageHelper;
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function modifyData(array $data)
    {
        foreach ($data as &$item) {
            $this->modifyImageData($item['product'], 'facebook');
            $this->modifyImageData($item['product'], 'twitter');
        }

        return $data;
    }

    protected function modifyImageData(&$item, $type)
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
     * @inheritDoc
     */
    public function modifyMeta(array $meta)
    {
        $path = $this->config->getProductsAttributeGroupCode();

        $attributeGroup['arguments']['data']['config'] = [
            'label' => 'YoastSeo',
            'component' => 'MaxServ_YoastSeo/js/form/component/fieldset/yoast-box'
        ];
        $meta = $this->arrayManager->merge($path, $meta, $attributeGroup);

        $meta = $this->updateFieldSet($path, $meta, 'facebook');
        $meta = $this->updateFieldSet($path, $meta, 'twitter');

        return $meta;
    }

    protected function updateFieldSet($path, $meta, $type)
    {
        $fields = [
            'container_yoast_' . $type . '_title',
            'container_yoast_' . $type . '_description',
        ];

        $fieldSet['arguments']['data']['config'] = [
            'collapsible' => false,
            'componentType' => 'fieldset',
            'label' => ''
        ];

        foreach ($fields as $field) {
            $index = $this->arrayManager->findPath($field, $meta);
            $fieldData = $this->arrayManager->get($index, $meta);
            $meta = $this->arrayManager->remove($index, $meta);
            $fieldSet['children'][$field] = $fieldData;
        }

        $meta = $this->arrayManager->set(
            $path . '/children/yoast_' . $type,
            $meta,
            $fieldSet
        );

        return $meta;
    }
}
