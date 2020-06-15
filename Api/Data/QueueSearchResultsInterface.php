<?php

namespace Lima\Movie\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface QueueSearchResultsInterface
 * @package Lima\Movie\Api\Data
 */
interface QueueSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Magento\Framework\Api\ExtensibleDataInterface[]
     */
    public function getItems();

    /**
     * @param array $items
     * @return QueueSearchResultsInterface
     */
    public function setItems(array $items);
}

