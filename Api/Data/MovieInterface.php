<?php

namespace Peteleco\Movie\Api\Data;

interface MovieInterface extends \Magento\Framework\Api\CustomAttributesDataInterface
{
    const ID = 'id';
    const TMDB_ID = 'tmdb_id';
    const TITLE = 'title';
    const POSTER_PATH = 'poster_path';
    const OVERVIEW = 'overview';
    const ENABLED = 'enabled';

    /**
     * Get movie id
     *
     * @return int|null
     */
    public function getId();
}
