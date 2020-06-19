<?php

namespace Peteleco\Movie\Model\ResourceModel\MovieFavorite\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface as Logger;

class Collection extends \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
{
    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        $mainTable = 'mv_movie_favorite',
        $resourceModel = 'Peteleco\Movie\Model\ResourceModel\MovieFavorite'
    )
    {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $mainTable,
            $resourceModel
        );
    }

    protected function _initSelect()
    {
        parent::_initSelect();

        $this->join(
            [$this->getTable('mv_movie')],
            'mv_movie_favorite.movie_id = ' . $this->getTable('mv_movie') . '.movie_id',
            ['title','poster_path']
        );

        return $this;
    }
}
