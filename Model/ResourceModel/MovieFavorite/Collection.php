<?php

namespace Peteleco\Movie\Model\ResourceModel\MovieFavorite;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Peteleco\Movie\Model\MovieFavorite;
use Peteleco\Movie\Model\ResourceModel\MovieFavorite as MovieFavoriteResource;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(MovieFavorite::class, MovieFavoriteResource::class);
    }
}
