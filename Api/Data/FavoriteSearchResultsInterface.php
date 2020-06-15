<?php

namespace Lima\Movie\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface FavoriteSearchResultsInterface
 * @package Lima\Movie\Api\Data
 */
interface FavoriteSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Magento\Framework\Api\ExtensibleDataInterface[]
     */
    public function getItems();

    /**
     * @param array $items
     * @return FavoriteSearchResultsInterface
     */
    public function setItems(array $items);
}

