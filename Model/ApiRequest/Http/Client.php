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

    public function requestGetByIdCurl(RequestInterface $request)
    {
        $DocumentId = $request->getParams()['id'] ?: null;
        // @codingStandardsIgnoreStart
        curl_setopt($this->curl, CURLOPT_URL, $request->getUri() . $DocumentId);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_HTTPGET, true);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 60);
        $response = curl_exec($this->curl);
        curl_close($this->curl);
        // @codingStandardsIgnoreEnd
        return $response;
    }

    public function requestPostByCurl(RequestInterface $request)
    {
        // @codingStandardsIgnoreStart
        curl_setopt($this->curl, CURLOPT_URL, $request->getUri());
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($request->getParams()));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 60);
        $response = curl_exec($this->curl);
        curl_close($this->curl);
        // @codingStandardsIgnoreEnd
        return $response;
    }
}

