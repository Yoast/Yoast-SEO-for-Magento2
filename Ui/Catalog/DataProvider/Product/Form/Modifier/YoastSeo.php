<?php

namespace MaxServ\YoastSeo\Ui\Catalog\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use MaxServ\YoastSeo\Helper\Config;

class YoastSeo extends AbstractModifier
{
    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param ArrayManager $arrayManager
     * @param Config $config
     */
    public function __construct(
        ArrayManager $arrayManager,
        Config $config
    ) {
        $this->arrayManager = $arrayManager;
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function modifyMeta(array $meta)
    {
        $path = $this->config->getProductsAttributeGroupCode();

        $yoastBox['arguments']['data']['config'] = [
            'componentType' => 'text',
            'component' => 'MaxServ_YoastSeo/js/view/yoast-box'
        ];

        $attributeGroup = [];
        $attributeGroup['arguments']['data']['config']['label'] = 'YoastSEO';

        $attributeGroup['children'] = [
            'yoastbox' => $yoastBox
        ];

        $meta = $this->arrayManager->merge($path, $meta, $attributeGroup);

        return $meta;
    }
}
