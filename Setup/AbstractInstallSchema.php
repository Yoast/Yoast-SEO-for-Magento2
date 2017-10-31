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

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class AbstractInstallSchema
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
     * @return AdapterInterface
     */
    protected function getSetupConnection()
    {
        if (empty($this->setupConnection)) {
            $this->setupConnection = $this->setup->getConnection();
        }

        return $this->setupConnection;
    }

    protected function updateCmsPageColumns()
    {
        $columns = [
            'focus_keyword' => ['comment' => 'Focus Keyword'],
            'yoast_facebook_title' => ['comment' => 'Yoast Facebook Title'],
            'yoast_facebook_description' => ['comment' => 'Yoast Facebook Description', 'length' => 0],
            'yoast_facebook_image' => ['comment' => 'Yoast Facebook Image'],
            'yoast_twitter_title' => ['comment' => 'Yoast Twitter Title'],
            'yoast_twitter_description' => ['comment' => 'Yoast Twitter Description', 'length' => 0],
            'yoast_twitter_image' => ['comment' => 'Yoast Twitter Image'],
            'yoast_robots_instructions' => ['comment' => 'Yoast Robot instructions'],
        ];
        $cmsTableName = $this->setup->getTable('cms_page');
        foreach ($columns as $columnName => $columnDefinition) {
            $this->addColumn($cmsTableName, $columnName, $columnDefinition);
        }
    }

    /**
     * Add a column to the specified table if it does not exist yet.
     *
     * @param string $tableName
     * @param string $columnName
     * @param array $columnDefinition
     */
    protected function addColumn($tableName, $columnName, $columnDefinition = [])
    {
        if (!$this->getSetupConnection()->tableColumnExists($tableName, $columnName)) {
            $columnDefinition = array_merge(
                [
                    "type" => Table::TYPE_TEXT,
                    "length" => 255,
                    "comment" => "No Comment"
                ],
                $columnDefinition
            );
            if ($columnDefinition['length'] <= 0) {
                unset($columnDefinition['length']);
            }
            $this->getSetupConnection()->addColumn(
                $tableName,
                $columnName,
                $columnDefinition
            );
        }
    }
}
