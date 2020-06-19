<?php

namespace Peteleco\Movie\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class MovieFavorite extends AbstractDb
{
//    protected $_uniqueFields = [
//        'field' => 'tmdb_id', 'title' => 'This id should be unique, check this movie has already been added.'
//    ];

    public function _construct()
    {
        $this->_init('mv_movie_favorite', 'id');
    }
}
