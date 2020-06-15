<?php

namespace Lima\Movie\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Lima\Movie\Api\Data\FavoriteInterface;

/**
 * Interface FavoriteRepositoryInterface
 * @package Lima\Movie\Api
 */
interface FavoriteRepositoryInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param FavoriteInterface $favorite
     * @return mixed
     */
    public function save(FavoriteInterface $favorite);

    /**
     * @param $favoriteId
     * @return mixed
     */
    public function delete($favoriteId);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
