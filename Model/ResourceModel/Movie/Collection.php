<?php

namespace Peteleco\Movie\Model\ResourceModel\Movie;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Peteleco\Movie\Model\Movie;
use Peteleco\Movie\Model\ResourceModel\Movie as MovieResource;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(Movie::class, MovieResource::class);
    }
}
