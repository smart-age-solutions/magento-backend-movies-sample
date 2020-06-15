<?php

namespace Lima\Movie\Model\ResourceModel\Favorite;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
	protected $_idFieldName = 'entity_id';

    /**
     * @var string
     */
	protected $_eventPrefix = 'movie_favorite_collection';

    /**
     * @var string
     */
	protected $_eventObject = 'movie_favorite_collection';

    /**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Lima\Movie\Model\Favorite', 'Lima\Movie\Model\ResourceModel\Favorite');
	}

    /**
     * @return $this|Collection|void
     */
    protected function _initSelect()
    {
        parent::_initSelect();

        $this->getSelect()
            ->columns(['cpe.sku', 'count' => 'COUNT(*)'])
            /*
            ->joinLeft(
                ['ce' => $this->getTable('customer_entity')],
                'ce.entity_id = main_table.customer_id',
                ['ce.firstname', 'ce.lastname']
            )
            */
            ->joinLeft(
                ['cpe' => $this->getTable('catalog_product_entity')],
                'cpe.entity_id = main_table.product_id',
                []
            )
            ->joinLeft(
                ['eet' => 'eav_entity_type'],
                "eet.entity_type_code = 'catalog_product'",
                []
            )
            ->joinLeft(
                ['ea' => 'eav_attribute'],
                "ea.entity_type_id = eet.entity_type_id AND ea.attribute_code = 'name'",
                []
            )
            ->joinLeft(
                ['cpev' => 'catalog_product_entity_varchar'],
                "cpev.entity_id = cpe.entity_id AND cpev.attribute_id = ea.attribute_id",
                ['name' => 'value']
            )
            ->group('main_table.product_id')
            ->order("count DESC")
            ->limit(10);
    }

}
