<?php

namespace Juniorfreitas\Movie\Model;

use Magento\Framework\Model\AbstractModel;

class Config extends AbstractModel
{
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getApiKey()
    {
        return $this->scopeConfig->getValue('movies/general/api_key');
    }

}
