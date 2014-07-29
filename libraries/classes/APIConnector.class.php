<?php

class APIConnector
{
    const REST_API_USERNAME = 'chameleon-api';
    const REST_API_PASSWORD = 'BwHwEpJnIqUgWkOv9YDg';

    private $serviceUrl;
    private $serviceCalls;

    public function __construct()
    {
        $this->serviceUrl = 'http://bidder.mediadecision.lan:8080/chameleon-0.1/rest';
        $this->serviceCalls = array();
        $this->serviceCalls['getTemplates'] = 'advertiser/{advertiserId}/bannerTemplates';
        $this->serviceCalls['postTemplate'] = 'bannerTemplates';
    }


    public function getMethodList()
    {
        $methodList = array_keys($this->serviceCalls);
        return $methodList;
    }

    public function get($path)
    {
        $restCall = $path;
        $response = file_get_contents($restCall);
        return $response;
    }

    public function getTemplates($advertiserId)
    {
        $serviceUrl = $this->serviceUrl . '/' . str_replace('{advertiserId}', $advertiserId, $this->serviceCalls['getTemplates']);
        echo $serviceUrl . "\n\n";

        $curl = $this->getCurl($serviceUrl, 'GET');

        $curlResponse = curl_exec($curl);
        curl_close($curl);
        var_dump($curlResponse);
        return $curlResponse;
    }

    public function sendBannerTemplate($template)
    {
        $serviceUrl = $this->serviceUrl . '/' . $this->serviceCalls['postTemplate'];
        $curl = $this->getCurl($serviceUrl, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, array('bannerTemplateModel' => json_encode($template)));

        $curlResponse = curl_exec($curl);
        curl_close($curl);
        echo "\n" . 'Response: ' . "\n";
        var_dump($curlResponse);
        return $curlResponse;
    }


    private function getCurl($serviceUrl, $method)
    {
        $curl = curl_init($serviceUrl);
        $baseAuthUserPwd = (APIConnector::REST_API_USERNAME . ':' . APIConnector::REST_API_PASSWORD);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERPWD, $baseAuthUserPwd);
        if($method === 'GET')
        {
            curl_setopt($curl, CURLOPT_HTTPGET, true);
        }
        else if($method === 'PUT')
        {
            curl_setopt($curl, CURLOPT_PUT, true);
        }
        else if ($method === 'POST')
        {
            curl_setopt($curl, CURLOPT_POST, true);
        }

        return $curl;
    }
}

function isJson($string)
{
    json_decode($string);
    return json_last_error() == JSON_ERROR_NONE;
}
