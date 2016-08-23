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

namespace MaxServ\YoastSeo\Setup;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class AbstractInstall
{

    /**
     * @var ModuleContextInterface
     */
    protected $context;

    /**
     * @var SchemaSetupInterface
     */
    protected $setup;

    /**
     * @var AdapterInterface
     */
    protected $setupConnection;

    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * @var EavSetup
     */
    protected $eavSetup;

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

    /**
     * Add a column to the cms_page table if it does not exist yet.
     *
     * @param string $columnName
     * @param array $columnDefinition
     */
    protected function addCmsPageColumn($columnName, $columnDefinition = [])
    {
        $tableName = $this->setupConnection->getTableName('cms_page');
        if (!$this->setupConnection->tableColumnExists($tableName, $columnName)) {
            $columnDefinition = array_merge(
                [
                    "type" => Table::TYPE_TEXT,
                    "length" => 255,
                    "comment" => "No Comment"
                ],
                $columnDefinition
            );
            $this->setupConnection->addColumn(
                $tableName,
                $columnName,
                $columnDefinition
            );
        }
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

}
