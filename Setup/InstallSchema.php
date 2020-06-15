<?php

namespace Lima\Movie\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;

/**
 * Class InstallData
 * @package Lima\Movie\Setup
 */
class InstallData implements InstallDataInterface
{
    const ATTRIBUTE_SET_ID_CONFIG_PATH  = 'movie/movie_default/attribute_set_id';
    const MOVIE_QUEUE_TABLE = 'movie_queue';
    const MOVIE_FAVORITE_TABLE = 'movie_favorite';

    /**
     * @var EavSetupFactory
     */
	private $eavSetupFactory;

    /**
     * @var AttributeSetFactory
     */
	private $attributeSetFactory;

    /**
     * @var CategorySetupFactory
     */
	private $categorySetupFactory;

    /**
     * @var ConfigInterface
     */
	private $resourceConfig;

    /**
     * InstallData constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     * @param CategorySetupFactory $categorySetupFactory
     * @param ConfigInterface $resourceConfig
     */
	public function __construct(
		EavSetupFactory $eavSetupFactory,
		AttributeSetFactory $attributeSetFactory,
		CategorySetupFactory $categorySetupFactory,
        ConfigInterface  $resourceConfig
	) {
		$this->eavSetupFactory = $eavSetupFactory;
		$this->attributeSetFactory = $attributeSetFactory;
		$this->categorySetupFactory = $categorySetupFactory;
		$this->resourceConfig = $resourceConfig;
	}

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Db_Exception
     * @throws \Zend_Validate_Exception
     */
	public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
	{
		/**
		 * Create table 'movie_queue'
		*/
		$table = $setup->getConnection()
			->newTable($setup->getTable(self::MOVIE_QUEUE_TABLE))
			->addColumn(
				'import_id',
				\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				null,
				[
					'identity' => true,
					'unsigned' => true,
					'nullable' => false,
					'primary' => true
				],
				'Import ID'
			)
			->addColumn(
				'movie_id',
				\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				null,
				[
					'unsigned' => true,
					'nullable' => false
				],
				'Movie ID'
			)
            ->addColumn(
				'attribute_set_id',
				\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				null,
				[
					'unsigned' => true,
					'nullable' => false
				],
				'Attribute Set Id'
			)
			->addColumn(
                'price',
                \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                null,
                [
					'default' => 0
				],
                'Price'
            )
            ->addColumn(
                'stock',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
					'default' => 0
				],
                'Stock'
            )
            ->addColumn(
                'image',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                254,
                ['nullable' => true],
                'Image URL'
            )
            ->addColumn(
                'language',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                254,
                ['nullable' => true],
                'Language'
            )
            ->addColumn(
                'title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Title'
            )
            ->addColumn(
                'overview',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                11222222,
                ['nullable' => true],
                'Overview'
            )
            ->addColumn(
                'adult',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                1,
                [
					'default' => 0
				],
                'Is Adult'
            )
            ->addColumn(
                'release_date',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [
					'nullable' => true
				],
                'Release Date'
			)
			->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [
					'nullable' => true,
					'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE
				],
                'Created At'
			)
			->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [
					'nullable' => true
				],
                'Updated At'
			)
			->addColumn(
                'pending',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                1,
                [
					'default' => 1
				],
                'Is Pending'
            )
			->setComment("Movie Import Queue");

		$setup->getConnection()->createTable($table);

        /**
         * Create table 'movie_favorite'
         */
        $customerTable = $setup->getTable('customer_entity');
        $productTable = $setup->getTable('catalog_product_entity');

        $table = $setup->getConnection()
            ->newTable($setup->getTable(self::MOVIE_FAVORITE_TABLE))
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Import ID'
            )
            ->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'Product ID'
            )
            ->addColumn(
                'customer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'Customer ID'
            )
           ->addForeignKey(
                self::MOVIE_FAVORITE_TABLE . '_customer',
                'customer_id',
                $customerTable,
                'entity_id',
               \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                self::MOVIE_FAVORITE_TABLE . '_product',
                'product_id',
                $productTable,
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment("Movie Favorite List");

        $setup->getConnection()->createTable($table);

		/*
		* Creating Movie attribute set
		*/
		$categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);

		$attributeSet = $this->attributeSetFactory->create();
		$entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
		$attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
		$data = [
			'attribute_set_name' => 'Movie',
			'entity_type_id' => $entityTypeId,
			'sort_order' => 200,
		];
		$attributeSet->setData($data);
		$attributeSet->validate();
		$attributeSet->save();
		$attributeSet->initFromSkeleton($attributeSetId);
		$attributeSet->save();

		/*
		* Creating movie attributes
		*/
		$eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

		$eavSetup->addAttribute(
			\Magento\Catalog\Model\Product::ENTITY,
			'is_movie',
			[
				'type' => 'int',
				'backend' => '',
				'frontend' => '',
				'label' => 'Is a movie',
				'input' => 'boolean',
				'class' => '',
				'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
				'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
				'visible' => false,
				'required' => false,
				'user_defined' => false,
				'default' => '0',
				'searchable' => false,
				'filterable' => false,
				'comparable' => false,
				'visible_on_front' => false,
				'used_in_product_listing' => false,
				'unique' => false,
				'apply_to' => '',
				'attribute_set_id' => $attributeSet->getId(),
			]
		)
		->addAttribute(
			\Magento\Catalog\Model\Product::ENTITY,
			'movie_id',
			[
				'type' => 'int',
				'backend' => '',
				'frontend' => '',
				'label' => 'Movie ID',
				'input' => 'text',
				'class' => '',
				'source' => '',
				'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
				'visible' => true,
				'required' => false,
				'user_defined' => false,
				'default' => '0',
				'searchable' => false,
				'filterable' => false,
				'comparable' => false,
				'visible_on_front' => false,
				'used_in_product_listing' => false,
				'unique' => false,
				'apply_to' => '',
				'attribute_set_id' => $attributeSet->getId(),
			]
		)->addAttribute(
			\Magento\Catalog\Model\Product::ENTITY,
			'movie_language',
			[
				'type' => 'varchar',
				'label' => 'Language',
				'input' => 'text',
				'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
				'visible' => true,
				'required' => false,
				'user_defined' => false,
				'default' => 'en-US',
				'searchable' => false,
				'filterable' => false,
				'comparable' => false,
				'visible_on_front' => false,
				'used_in_product_listing' => false,
				'unique' => false,
				'apply_to' => '',
				'attribute_set_id' => $attributeSet->getId(),
			]
		)->addAttribute(
           \Magento\Catalog\Model\Product::ENTITY,
            'movie_release_date',
            [
                'type' => 'datetime',
                'backend' => '',
                'frontend' => 'Magento\Eav\Model\Entity\Attribute\Frontend\Datetime',
                'label' => 'Release Date',
                'input' => 'date',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => '',
                'sort_order' => 9,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => '',
                'attribute_set_id' => $attributeSet->getId(),
            ]
        )->addAttribute(
			\Magento\Catalog\Model\Product::ENTITY,
			'movie_adult',
			[
				'type' => 'int',
				'backend' => '',
				'frontend' => '',
				'label' => 'Is Adult',
				'input' => 'boolean',
				'class' => '',
				'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
				'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
				'visible' => false,
				'required' => false,
				'user_defined' => false,
				'default' => '0',
				'searchable' => false,
				'filterable' => false,
				'comparable' => false,
				'visible_on_front' => false,
				'used_in_product_listing' => false,
				'unique' => false,
				'apply_to' => '',
				'attribute_set_id' => $attributeSet->getId(),
			]
		);

        /**
         *  Add This attribute set to config
         */
        $this->resourceConfig->saveConfig(
            self::ATTRIBUTE_SET_ID_CONFIG_PATH,
            $attributeSet->getId(),
            \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            \Magento\Store\Model\Store::DEFAULT_STORE_ID
        );

		$setup->endSetup();
	}
}
