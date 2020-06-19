<?php

namespace Peteleco\Movie\Helper;
/**
 * Class Data
 * @package Peteleco\Movie\Helper
 * @see https://magento.stackexchange.com/questions/84481/magento-2-how-to-get-the-extensions-configuration-values-in-the-phtml-files
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Return config option
     * @param $config_path
     * @return mixed
     */
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Return full path of poster
     * @param null $path
     * @return string
     */
    public function getFullPosterPath($path = null)
    {
        if (!$path || empty($path)) {
            return 'default.jpg';
        }
        return 'https://image.tmdb.org/t/p/w200/' . $path;
    }
}
