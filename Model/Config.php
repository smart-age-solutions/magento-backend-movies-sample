<?php

namespace Peteleco\Movie\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    const XML_PATH_ENABLED_SEARCH_ON_TMDB = 'movie/general/enable_search_on_tmdb';

    private $config;

    public function __construct(ScopeConfigInterface $config)
    {
        $this->config = $config;
    }

    public function isEnabledSearchOnTmdb()
    {
        return $this->config->getValue(self::XML_PATH_ENABLED_SEARCH_ON_TMDB);
    }
}
