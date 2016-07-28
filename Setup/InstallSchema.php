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


use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * @var SchemaSetupInterface
     */
    protected $setup;

    /**
     * @var ModuleContextInterface
     */
    protected $context;

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->setup = $setup;
        $this->context = $context;

        $this->addCmsPageColumns();
        $this->addProductAttributes();
        $this->addCategoryAttributes();
    }

    protected function addCmsPageColumns()
    {
        $setupConnection = $this->setup->getConnection();

    }

    protected function addCmsPageColumn($setupConnection, $columnName, $columnDefinition = [])
    {
        $columnDefinition = array_merge(
            [
                "type" => Table::TYPE_TEXT,
                "length" => 255,
                "comment" => "No Comment"
            ],
            $columnDefinition
        );
    }

    protected function addProductAttributes()
    {

    }

    protected function addCategoryAttributes()
    {

    }
}
