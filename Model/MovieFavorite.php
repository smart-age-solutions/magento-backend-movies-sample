<?php

namespace Peteleco\Movie\Model;

use Magento\Framework\Model\AbstractModel;

class MovieFavorite extends AbstractModel
{
    protected $_eventPrefix = 'mv_movie_favorite';
    protected function _construct()
    {
        $this->_init(\Peteleco\Movie\Model\ResourceModel\MovieFavorite::class);
    }
}
