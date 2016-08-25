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

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallSchemaData extends AbstractInstallData implements InstallDataInterface
{

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->setup = $setup;
        $this->context = $context;

        $this->updateProductAttributes();
        $this->updateCategoryAttributes();
    }

    protected function updateProductAttributes()
    {
        $this->addProductAttribute('focus_keyword', [
            'label' => 'Focus Keyword',
            'group' => 'Search Engine Optimization',
            'sort_order' => 100
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
    }
}
