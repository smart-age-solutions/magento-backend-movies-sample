<?php

namespace Lima\Movie\Helper;

/**
 * Class Favorite
 * @package Lima\Movie\Helper
 */
class Favorite extends \Magento\Framework\Url\Helper\Data
{
    const FAVORITE_ADD_URL = 'movie/favorite/add';

    public function getAddUrl($product, $additional = [])
    {
        if (isset($additional['useUencPlaceholder'])) {
            $uenc = "%uenc%";
            unset($additional['useUencPlaceholder']);
        } else {
            $uenc = $this->urlEncoder->encode($this->_urlBuilder->getCurrentUrl());
        }

        $urlParamName = \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED;

        $routeParams = [
            $urlParamName => $uenc,
            'product' => $product->getEntityId(),
            '_secure' => $this->_getRequest()->isSecure()
        ];

        if (!empty($additional)) {
            $routeParams = array_merge($routeParams, $additional);
        }

        if ($product->hasUrlDataObject()) {
            $routeParams['_scope'] = $product->getUrlDataObject()->getStoreId();
            $routeParams['_scope_to_url'] = true;
        }

        return $this->_getUrl(self::FAVORITE_ADD_URL, $routeParams);
    }
}
