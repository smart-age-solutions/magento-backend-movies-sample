<?php

namespace Lima\Movie\Model\ResourceModel\Queue;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Lima\Movie\Model\ResourceModel\Queue
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
	protected $_idFieldName = 'import_id';

    /**
     * @var string
     */
	protected $_eventPrefix = 'movie_queue_collection';

    /**
     * @var string
     */
	protected $_eventObject = 'movie_queue_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Lima\Movie\Model\Queue', 'Lima\Movie\Model\ResourceModel\Queue');
	}

}
