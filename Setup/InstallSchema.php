<?php

namespace Peteleco\Movie\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $table = $setup->getConnection()->newTable(
            $setup->getTable('mv_movie')
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
            'Item ID'
        )->addColumn(
            'title',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Movie title'
        )->addIndex(
            $setup->getIdxName('mv_movie', ['title']),
            ['title']
        )->addColumn(
            'overview',
            Table::TYPE_TEXT,
            511,
            ['nullable' => false],
            'Movie title'
        )->setComment(
            'Movies'
        );
        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
