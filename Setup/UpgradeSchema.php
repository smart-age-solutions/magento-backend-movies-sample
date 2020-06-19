<?php

namespace Peteleco\Movie\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        // V2 Upgrade Schema
        if (version_compare($context->getVersion(), '2.0.0', '<')) {
            // Add tmdb_id movie field, with this field we will be able sync with TMDB
            $setup->getConnection()->addColumn(
                $setup->getTable('mv_movie'),
                'tmdb_id',
                [
                    'type' => Table::TYPE_BIGINT,
                    // This option are allowed
                    'nullable' => true,
                    'comment' => 'Movie id at TMDB'
                ]
            );
            $setup->getConnection()->addIndex(
                $setup->getTable('mv_movie'),
                $setup->getIdxName('mv_movie', ['tmdb_id'], AdapterInterface::INDEX_TYPE_UNIQUE),
                ['tmdb_id']
            );

            // Add enabled field to avoid delete operations and getting trouble with cascade delete
            $setup->getConnection()->addColumn(
                $setup->getTable('mv_movie'),
                'enabled',
                [
                    'type' => Table::TYPE_BOOLEAN,
                    // This option are allowed
                    'nullable' => false,
                    'default' => 1,
                    'comment' => 'Is movie enabled to be liked'
                ]
            );
            $setup->getConnection()->addIndex(
                $setup->getTable('mv_movie'),
                $setup->getIdxName('mv_movie', ['enabled'], AdapterInterface::INDEX_TYPE_INDEX),
                ['enabled']
            );
        }

        if (version_compare($context->getVersion(), '3.0.0', '<')) {
            // Add poster path  field
            $setup->getConnection()->addColumn(
                $setup->getTable('mv_movie'),
                'poster_path',
                [
                    'type' => Table::TYPE_TEXT,
                    'size' => 255,
                    'nullable' => true,
                    'comment' => 'Movie poster at TMDB'
                ]
            );
            // Store id
            $setup->getConnection()->addColumn(
                $setup->getTable('mv_movie'),
                'store_id',
                [
                    'type' => Table::TYPE_SMALLINT,
                    'unsigned' => true,
                    'nullable' => true,
                    'comment' => 'Store id'
                ]
            );

            $setup->getConnection()->addIndex(
                $setup->getTable('mv_movie'),
                $setup->getIdxName('mv_movie', ['store_id'], AdapterInterface::INDEX_TYPE_INDEX),
                ['store_id']
            );

            $setup->getConnection()->addForeignKey(
                $setup->getFkName('mv_movie', 'store_id', 'store', 'store_id'),
                $setup->getTable('mv_movie'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
            );

            // Movie liked table
            $table = $setup->getConnection()->newTable(
                $setup->getTable('mv_movie_favorite')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => true, 'unsigned' => true],
                'Store id'
            )->addIndex(
                $setup->getIdxName('mv_movie_favorite', ['store_id']),
                ['store_id']
            )->addForeignKey(
                $setup->getFkName('mv_movie_favorite', 'store_id', 'store', 'store_id'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
            )->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => true, 'unsigned' => true],
                'Customer Id'
            )->addIndex(
                $setup->getIdxName('mv_movie_favorite', ['customer_id']),
                ['customer_id']
            )->addForeignKey(
                $setup->getFkName('mv_movie_favorite', 'store_id', 'store', 'store_id'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
            )->addColumn(
                'movie_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => true, 'unsigned' => true],
                'Movie Id'
            )->addIndex(
                $setup->getIdxName('mv_movie_favorite', ['movie_id']),
                ['movie_id']
            )->addForeignKey(
                $setup->getFkName('mv_movie_favorite', 'movie_id', 'mv_movie', 'id'),
                'movie_id',
                $setup->getTable('mv_movie'),
                'id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment('Favorite movies');
            $setup->getConnection()->createTable($table);
        }

        $setup->endSetup();
    }
}
