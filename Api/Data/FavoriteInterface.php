<?php

namespace Lima\Movie\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface FavoriteInterface
 * @package Lima\Movie\Api\Data
 */
interface FavoriteInterface extends ExtensibleDataInterface
{
    /**
     * @return mixed
     */
    public function getEntityId();

    /**
     * @param $entityId
     * @return mixed
     */
    public function setEntityId($entityId);

    /**
     * @return mixed
     */
    public function getProductId();

    /**
     * @param $productId
     * @return mixed
     */
    public function setProductId($productId);

    /**
     * @return mixed
     */
    public function getCustomerId();

    /**
     * @param $customerId
     * @return mixed
     */
    public function setCustomerId($customerId);
}
