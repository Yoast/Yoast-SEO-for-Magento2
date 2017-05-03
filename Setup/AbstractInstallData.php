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

namespace MaxServ\YoastSeo\Setup;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class AbstractInstallData
{

    /**
     * @var ModuleContextInterface
     */
    protected $context;

    /**
     * @var ModuleDataSetupInterface
     */
    protected $setup;

    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * @var EavSetup
     */
    protected $eavSetup;

    /**
     * AbstractInstallData constructor.
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @return EavSetup
     */
    protected function getEavSetup()
    {
        if (empty($this->eavSetup) && $this->setup instanceof ModuleDataSetupInterface) {
            $this->eavSetup = $this->eavSetupFactory->create(['setup' => $this->setup]);
        }
        return $this->eavSetup;
    }

    protected function updateProductAttributes()
    {
        $this->addProductAttribute('focus_keyword', [
            'label' => 'Focus Keyword',
            'group' => 'Search Engine Optimization',
            'sort_order' => 100
        ]);
        $this->addProductAttribute('yoast_robots_instructions', [
            'label' => 'Robot instructions',
            'group' => 'Search Engine Optimization',
            'input' => 'select',
            'source' => 'MaxServ\YoastSeo\Model\Entity\Attribute\Source\Robots',
            'sort_order' => 110
        ]);
        $this->addProductAttribute('yoast_facebook_title', [
            'label' => 'Facebook title',
            'group' => 'Yoast Facebook',
            'sort_order' => 10
        ]);
        $this->addProductAttribute('yoast_facebook_description', [
            'label' => 'Facebook description',
            'type' => 'text',
            'input' => 'textarea',
            'group' => 'Yoast Facebook',
            'sort_order' => 20
        ]);
        $this->addProductAttribute('yoast_facebook_image', [
            'input' => 'fileUploader',
            'backend' => 'MaxServ\YoastSeo\Model\Attribute\Backend\Image',
            'label' => 'Facebook image',
            'group' => 'Yoast Facebook',
            'sort_order' => 20
        ]);
        $this->addProductAttribute('yoast_twitter_title', [
            'label' => 'Twitter title',
            'group' => 'Yoast Twitter',
            'sort_order' => 10
        ]);
        $this->addProductAttribute('yoast_twitter_description', [
            'label' => 'Twitter description',
            'type' => 'text',
            'input' => 'textarea',
            'group' => 'Yoast Twitter',
            'sort_order' => 20
        ]);
        $this->addProductAttribute('yoast_twitter_image', [
            'input' => 'fileUploader',
            'backend' => 'MaxServ\YoastSeo\Model\Attribute\Backend\Image',
            'label' => 'Twitter image',
            'group' => 'Yoast Twitter',
            'sort_order' => 20
        ]);
    }

    protected function updateCategoryAttributes()
    {
        $this->addCategoryAttribute('focus_keyword', [
            'type' => 'varchar',
            'label' => 'Focus Keyword',
            'group' => 'Search Engine Optimization',
            'sort_order' => 100
        ]);
        $this->addCategoryAttribute('yoast_robots_instructions', [
            'label' => 'Robot instructions',
            'group' => 'Search Engine Optimization',
            'input' => 'select',
            'source' => 'MaxServ\YoastSeo\Model\Entity\Attribute\Source\Robots',
            'sort_order' => 110
        ]);
        $this->addCategoryAttribute('yoast_facebook_title', [
            'label' => 'Facebook title',
            'group' => 'Yoast Facebook',
            'sort_order' => 10
        ]);
        $this->addCategoryAttribute('yoast_facebook_description', [
            'label' => 'Facebook description',
            'type' => 'text',
            'input' => 'textarea',
            'group' => 'Yoast Facebook',
            'sort_order' => 20
        ]);
        $this->addCategoryAttribute('yoast_facebook_image', [
            'input' => 'fileUploader',
            'backend' => 'MaxServ\YoastSeo\Model\Attribute\Backend\Image',
            'label' => 'Facebook image',
            'group' => 'Yoast Facebook',
            'sort_order' => 20
        ]);
        $this->addCategoryAttribute('yoast_twitter_title', [
            'label' => 'Twitter title',
            'group' => 'Yoast Twitter',
            'sort_order' => 10
        ]);
        $this->addCategoryAttribute('yoast_twitter_description', [
            'label' => 'Twitter description',
            'type' => 'text',
            'input' => 'textarea',
            'group' => 'Yoast Twitter',
            'sort_order' => 20
        ]);
        $this->addCategoryAttribute('yoast_twitter_image', [
            'input' => 'fileUploader',
            'backend' => 'MaxServ\YoastSeo\Model\Attribute\Backend\Image',
            'label' => 'Twitter image',
            'group' => 'Yoast Twitter',
            'sort_order' => 20
        ]);
    }

    /**
     * @param string $attributeCode
     * @param array $attributeConfig
     */
    protected function addProductAttribute($attributeCode, $attributeConfig)
    {
        $defaultAttributeConfig = [
            'type' => 'varchar',
            'label' => 'Focus Keyword',
            'backend' => '',
            'frontend' => '',
            'input' => 'text',
            'class' => '',
            'source' => '',
            'default' => '',
            'global' => 'store',
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => false,
            'unique' => false,
            'apply_to' => ''
        ];
        $requiredAttributeConfigKeys = ['type', 'label', 'input'];
        $attributeConfig = array_merge($defaultAttributeConfig, $attributeConfig);
        if (count(array_diff($requiredAttributeConfigKeys, array_keys($attributeConfig)))) {
            // error, missing required key
            return;
        }

        $eavSetup = $this->getEavSetup();
        if ($eavSetup) {
            $eavSetup->addAttribute(
                Product::ENTITY,
                $attributeCode,
                $attributeConfig
            );
        }
    }

    /**
     * @param string $attributeCode
     * @param array $attributeConfig
     */
    public function addCategoryAttribute($attributeCode, $attributeConfig)
    {
        $defaultAttributeConfig = [
            'type' => 'varchar',
            'required' => false,
            'sort_order' => 100,
            'input' => 'text',
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'General Information',
            'user_defined' => true,
            'is_used_in_grid' => true,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => true
        ];
        $requiredAttributeConfigKeys = ['type', 'label', 'input'];
        $attributeConfig = array_merge($defaultAttributeConfig, $attributeConfig);
        if (count(array_diff($requiredAttributeConfigKeys, array_keys($attributeConfig)))) {
            // error, missing required key
            return;
        }

        $eavSetup = $this->getEavSetup();
        if ($eavSetup) {
            $this->eavSetup->addAttribute(
                Category::ENTITY,
                $attributeCode,
                $attributeConfig
            );
        }
    }

    protected function updateMirasvitBlogAttributes()
    {
        $eavSetup = $this->getEavSetup();
        if ($eavSetup) {
            $blogEntityType = $eavSetup->getEntityType('blog_post');
            if (!$blogEntityType) {
                // Mirasvit Blog entity setup not done
                return;
            }
        }
        $this->addBlogAttribute('focus_keyword', [
            'label' => 'Focus Keyword',
            'sort_order' => 100
        ]);
        $this->addBlogAttribute('yoast_robots_instructions', [
            'label' => 'Robot instructions',
            'input' => 'select',
            'source' => 'MaxServ\YoastSeo\Model\Entity\Attribute\Source\Robots',
            'sort_order' => 110
        ]);
        $this->addBlogAttribute('yoast_facebook_title', [
            'label' => 'Facebook title',
            'sort_order' => 10
        ]);
        $this->addBlogAttribute('yoast_facebook_description', [
            'label' => 'Facebook description',
            'type' => 'text',
            'input' => 'textarea',
            'sort_order' => 20
        ]);
        $this->addBlogAttribute('yoast_facebook_image', [
            'input' => 'fileUploader',
            'backend' => 'MaxServ\YoastSeo\Model\Attribute\Backend\Image',
            'label' => 'Facebook image',
            'sort_order' => 20
        ]);
        $this->addBlogAttribute('yoast_twitter_title', [
            'label' => 'Twitter title',
            'sort_order' => 10
        ]);
        $this->addBlogAttribute('yoast_twitter_description', [
            'label' => 'Twitter description',
            'type' => 'text',
            'input' => 'textarea',
            'sort_order' => 20
        ]);
        $this->addBlogAttribute('yoast_twitter_image', [
            'input' => 'fileUploader',
            'backend' => 'MaxServ\YoastSeo\Model\Attribute\Backend\Image',
            'label' => 'Twitter image',
            'sort_order' => 20
        ]);
    }

    /**
     * @param $attributeCode
     * @param $attributeConfiguration
     */
    protected function addBlogAttribute($attributeCode, $attributeConfiguration)
    {
        $defaultAttributeConfig = [
            'type' => 'varchar',
            'required' => false,
            'sort_order' => 100,
            'input' => 'text',
            'user_defined' => true
        ];
        $requiredAttributeConfigKeys = ['type', 'label', 'input'];
        $attributeConfiguration = array_merge($defaultAttributeConfig, $attributeConfiguration);
        if (count(array_diff($requiredAttributeConfigKeys, array_keys($attributeConfiguration)))) {
            // error, missing required key
            return;
        }
        $eavSetup = $this->getEavSetup();
        if ($eavSetup) {
            $eavSetup->addAttribute(
                'blog_post',
                $attributeCode,
                $attributeConfiguration
            );
        }
    }
}
