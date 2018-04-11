<?php

namespace MaxServ\YoastSeo\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface as TemplateInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @var SchemaSetupInterface
     */
    protected $setup;

    /**
     * @inheritDoc
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $this->setup = $setup;

        $this->setup->startSetup();
        $this->updateTemplatesTable();
        $this->updateCmsPageTable();
        $this->setup->endSetup();
    }

    protected function updateTemplatesTable()
    {
        $connection = $this->setup->getConnection();
        $table = $this->setup->getTable(TemplateInterface::TABLE);
        if (!$this->setup->tableExists($table)) {
            $newTable = $connection->newTable($table);
            $newTable
                ->addColumn(
                    TemplateInterface::KEY_TEMPLATE_ID,
                    Table::TYPE_SMALLINT,
                    null,
                    [
                        'primary' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'identity' => true
                    ],
                    'Template id'
                )
                ->addColumn(
                    TemplateInterface::KEY_ENTITY_TYPE,
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Entity Type'
                )
                ->addColumn(
                    TemplateInterface::KEY_CONTENT,
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false],
                    'Template content'
                )
                ->addIndex(
                    $connection->getIndexName(
                        $table,
                        TemplateInterface::KEY_ENTITY_TYPE,
                        AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    TemplateInterface::KEY_ENTITY_TYPE,
                    ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
                );
            
            $connection->createTable($newTable);
        }
    }

    protected function updateCmsPageTable()
    {
        $connection = $this->setup->getConnection();
        $table = $this->setup->getTable('cms_page');

        if (!$connection->tableColumnExists($table, 'yoast_facebook_image')) {
            $connection->addColumn($table, 'yoast_facebook_image', [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'Yoast SEO Facebook Image'
            ]);
        }

        if (!$connection->tableColumnExists($table, 'yoast_focus_keyword')) {
            $connection->addColumn($table, 'yoast_focus_keyword', [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'Yoast Focus Keyword'
            ]);
        }

        if (!$connection->tableColumnExists($table, 'yoast_keyword_score')) {
            $connection->addColumn($table, 'yoast_keyword_score', [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'YoastSEO Keyword Score'
            ]);
        }

        if (!$connection->tableColumnExists($table, 'yoast_content_score')) {
            $connection->addColumn($table, 'yoast_content_score', [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'YoastSEO Content Score'
            ]);
        }

        if (!$connection->tableColumnExists($table, 'yoast_facebook_title')) {
            $connection->addColumn($table, 'yoast_facebook_title', [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'Yoast SEO Facebook Title'
            ]);
        }

        if (!$connection->tableColumnExists($table, 'yoast_facebook_description')) {
            $connection->addColumn($table, 'yoast_facebook_description', [
                'type' => Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Yoast SEO Facebook Description'
            ]);
        }

        if (!$connection->tableColumnExists($table, 'yoast_twitter_image')) {
            $connection->addColumn($table, 'yoast_twitter_image', [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'Yoast SEO Twitter Image'
            ]);
        }

        if (!$connection->tableColumnExists($table, 'yoast_twitter_title')) {
            $connection->addColumn($table, 'yoast_twitter_title', [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'Yoast SEO Twitter Title'
            ]);
        }

        if (!$connection->tableColumnExists($table, 'yoast_twitter_description')) {
            $connection->addColumn($table, 'yoast_twitter_description', [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'Yoast SEO Twitter Description'
            ]);
        }
    }
}
