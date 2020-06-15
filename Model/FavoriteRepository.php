<?php

namespace Lima\Movie\Model;

use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Lima\Movie\Api\Data\FavoriteSearchResultsInterfaceFactory;
use Lima\Movie\Api\FavoriteRepositoryInterface;
use Lima\Movie\Model\ResourceModel\Favorite as FavoriteResource;
use Lima\Movie\Model\ResourceModel\Favorite\CollectionFactory;

/**
 * Class FavoriteRepository
 * @package Lima\Movie\Model
 */
class FavoriteRepository implements FavoriteRepositoryInterface
{
    /**
     * @var FavoriteResource
     */
    private $favoriteResource;

    /**
     * @var FavoriteFactory
     */
    private $favoriteFactory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var FavoriteSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * FavoriteRepository constructor.
     * @param FavoriteResource $FavoriteResource
     * @param FavoriteFactory $FavoriteFactory
     * @param CollectionFactory $collectionFactory
     * @param FavoriteSearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        FavoriteResource $FavoriteResource,
        FavoriteFactory $FavoriteFactory,
        CollectionFactory $collectionFactory,
        FavoriteSearchResultsInterfaceFactory $searchResultsFactory
    )
    {
        $this->favoriteResource = $FavoriteResource;
        $this->favoriteFactory = $FavoriteFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @param \Lima\Movie\Api\Data\FavoriteInterface $favorite
     * @return mixed
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function save(\Lima\Movie\Api\Data\FavoriteInterface $favorite)
    {
        $this->favoriteResource->save($favorite);
        return $favorite->getEntityId();
    }

    /**
     * @param $favoriteId
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getById($favoriteId)
    {
        $favorite = $this->favoriteFactory->create();
        $this->favoriteResource->load($favorite, $favoriteId);
        if(!$favorite->getEntityId()) {
            throw new NoSuchEntityException(__('Favorite List does not exist'));
        }
        return $favorite;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
        /** @var Magento\Framework\Api\SortOrder $sortOrder */
        foreach ((array)$searchCriteria->getSortOrders() as $sortOrder) {
            $field = $sortOrder->getField();
            $collection->addOrder(
                $field,
                $this->getDirection($sortOrder->getDirection())
            );

        }

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->load();
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setCriteria($searchCriteria);

        $favorites=[];
        foreach ($collection as $favorite){
            $favorites[] = $favorite;
        }
        $searchResults->setItems($favorites);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @param \Lima\Movie\Api\Data\FavoriteInterface $favoriteId
     * @return bool|mixed
     * @throws \Exception
     */
    public function delete($favoriteId)
    {
        $favorite = $this->favoriteFactory->create();
        $favorite->setId($favoriteId);
        if( $this->favoriteResource->delete($favorite)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $direction
     * @return bool|string
     */
    private function getDirection($direction)
    {
        return $direction == SortOrder::SORT_ASC ?: SortOrder::SORT_DESC;
    }

    /**
     * @param $group
     * @param $collection
     */
    private function addFilterGroupToCollection($group, $collection)
    {
        $fields = $conditions = [];

        foreach($group->getFilters() as $filter){
            $condition = $filter->getConditionType() ?: 'eq';
            $field = $filter->getField();
            $value = $filter->getValue();
            $fields[] = $field;
            $conditions[] = [$condition=>$value];

        }
        $collection->addFieldToFilter($fields, $conditions);
    }
}
