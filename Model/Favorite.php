<?php

namespace Lima\Movie\Model;

use Magento\Framework\Model\AbstractModel;
use Lima\Movie\Api\Data\FavoriteInterface;

/**
 * Class Favorite
 * @package Lima\Movie\Model
 */
class Favorite extends AbstractModel implements FavoriteInterface
{
	protected function _construct()
	{
		$this->_init('Lima\Movie\Model\ResourceModel\Favorite');
	}

    /**
     * @return int|mixed
     */
	public function getEntityId()
    {
        return (int) $this->getData('entity_id');
    }

    /**
     * @param int $entityId
     * @return Favorite
     */
    public function setEntityId($entityId)
    {
        return $this->setData('entity_id', $entityId);
    }

    /**
     * @return int
     */
    public function getProductId()
    {
        return (int) $this->getData('product_id');
    }

    /**
     * @param $productId
     * @return Favorite
     */
    public function setProductId($productId)
    {
        return $this->setData('product_id', $productId);
    }

    /**
     * @return int
     */
    public function getCustomerId()
    {
        return (int) $this->getData('customer_id');
    }

    /**
     * @param $customerId
     * @return Favorite
     */
    public function setCustomerId($customerId)
    {
        return $this->setData('customer_id', $customerId);
    }
}
