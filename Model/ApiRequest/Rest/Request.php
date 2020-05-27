<?php

namespace Juniorfreitas\Movie\Model\ApiRequest\Rest;

use Juniorfreitas\Movie\Model\ApiRequest\Api\RequestInterface;

class Request extends \Magento\Framework\DataObject implements RequestInterface
{
    const METHOD    = 'method';
    const URI       = 'uri';
    const PARAMS    = 'params';

    /**
     * @inheritDoc
     */
    public function getMethod()
    {
        return $this->getData(static::METHOD);
    }

    /**
     * @inheritDoc
     */
    public function getUri()
    {
        return $this->getData(static::URI);
    }

    /**
     * @inheritDoc
     */
    public function getParams()
    {
        return $this->getData(static::PARAMS);
    }


    /**
     * @inheritDoc
     */
    public function setMethod($value)
    {
        $this->setData(static::METHOD, $value);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setUri($value)
    {
        $this->setData(static::URI, $value);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setParams(array $value)
    {
        $this->setData(static::PARAMS, $value);
        return $this;
    }

    public function getParamsToQueryString()
    {
        $param = '';

        foreach ($this->getData(static::PARAMS) as $key => $item) {
            $param.= $key.'='.$item;
        }

        return $param;
    }
}

