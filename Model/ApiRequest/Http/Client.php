<?php

namespace Juniorfreitas\Movie\Model\ApiRequest\Http;

use Juniorfreitas\Movie\Model\ApiRequest\Api\RequestInterface;

class Client
{
    protected $curl;

    protected $statusCode;

    public function __construct()
    {
        $this->curl = curl_init();
    }

    public function requestGetCurl(RequestInterface $request)
    {
        // @codingStandardsIgnoreStart
        curl_setopt($this->curl, CURLOPT_URL, $request->getUri().'?'.$request->getParamsToQueryString());
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 60);
        $response = curl_exec($this->curl);
        curl_close($this->curl);
        // @codingStandardsIgnoreEnd
        return $response;
    }
}

