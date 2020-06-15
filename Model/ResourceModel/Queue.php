<?php
namespace Lima\Movie\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Queue
 * @package Lima\Movie\Model\ResourceModel
 */
class Queue extends AbstractDb
{
    /**
     * Queue constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     */
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}

    /**
     *
     */
	protected function _construct()
	{
		$this->_init('movie_queue', 'import_id');
	}

}
