<?php

namespace Juniorfreitas\Movie\Model\ApiRequest\Api;

interface RequestInterface
{
    /**
     * @return string
     */
    public function getUri();

    /**
     * @return string
     */
    public function getParams();
}
