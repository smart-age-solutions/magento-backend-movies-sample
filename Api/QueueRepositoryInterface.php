<?php

namespace Lima\Movie\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Lima\Movie\Api\Data\QueueInterface;

/**
 * Interface QueueRepositoryInterface
 * @package Lima\Movie\Api
 */
interface QueueRepositoryInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param QueueInterface $queue
     * @return mixed
     */
    public function save(QueueInterface $queue);

    /**
     * @param QueueInterface $queue
     * @return mixed
     */
    public function delete(QueueInterface $queue);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
