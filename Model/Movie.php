<?php

namespace Peteleco\Movie\Model;

use Magento\Framework\Model\AbstractModel;

class Movie extends AbstractModel
{
    protected $_eventPrefix = 'mv_movie';
    protected function _construct()
    {
        $this->_init(\Peteleco\Movie\Model\ResourceModel\Movie::class);
    }
}
