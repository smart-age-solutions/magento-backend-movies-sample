<?php
namespace Lima\Movie\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Favorite
 * @package Lima\Movie\Model\ResourceModel
 */
class Favorite extends AbstractDb
{
    /**
     * Favorite constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     */
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}

	protected function _construct()
	{
		$this->_init('movie_favorite', 'entity_id');
	}

}
