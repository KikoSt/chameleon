<?php

class APIConnector
{
    const REST_API_USERNAME = 'chameleon-api';
    const REST_API_PASSWORD = 'BwHwEpJnIqUgWkOv9YDg';

    private $serviceUrl;
    private $serviceCalls;

    private $advertiserId;
    private $companyId;

    public function __construct()
    {
        $this->serviceUrl = 'http://bidder.mediadecision.lan:8080/chameleon-0.1/rest';
        $this->serviceCalls = array();
        $this->serviceCalls['getTemplates']   = 'advertiser/{advertiserId}/bannerTemplates';
        $this->serviceCalls['postTemplate']   = 'bannerTemplate';
        $this->serviceCalls['deleteTemplate'] = 'bannerTemplate/{templateId}';
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

    public function getNumTemplates($advertiserId)
    {
        $templateCount = count($this->getTemplates($advertiserId));
        return $templateCount;
    }

    public function getTemplates($advertiserId)
    {
        $resource = $this->serviceUrl . '/' . str_replace('{advertiserId}', $advertiserId, $this->serviceCalls['getTemplates']);
        $curl = $this->getCurl($resource, 'GET');

        $curlResponse = curl_exec($curl);
        curl_close($curl);

        $templateList = json_decode($curlResponse)->bannerTemplateModels;

        $templates = array();

        foreach($templateList AS $template)
        {
            $templ = new BannerTemplateModel();
            $templ->setIdAdvertiser($advertiserId);
            $templ->setIdAuditUser((int) $template->idAuditUser);
            $templ->setDescription((string) $template->description);
            $templ->setName((string) $template->name);
            $templ->setIdBannerTemplate((int) $template->idBannerTemplate);
            $templ->setIdParentBannerTemplate((int) $template->idParentBannerTemplate);
            $templ->setSvgContent($template->svgContent);
            $templates[] = $templ;
        }

        return $templates;
    }

    public function sendBannerTemplate($template)
    {
        $template = json_encode($template->jsonSerialize());
        $resource = $this->serviceUrl . '/' . $this->serviceCalls['postTemplate'];
        $curl = $this->getCurl($resource, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $template);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $curlResponse = curl_exec($curl);
        curl_close($curl);
        return $curlResponse;
    }

    public function deleteBannerTemplate($templateId)
    {
        $resource = $this->serviceUrl . '/' . str_replace('{templateId}', $templateId, $this->serviceCalls['deleteTemplate']);
        $curl = $this->getCurl($resource, 'DELETE');

        $curlResponse = curl_exec($curl);
        curl_close($curl);
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
        else if ($method === 'DELETE')
        {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        return $curl;
    }

    /**
     * Get advertiserId.
     *
     * @return advertiserId.
     */
    public function getAdvertiserId()
    {
        return $this->advertiserId;
    }

    /**
     * Set advertiserId.
     *
     * @param advertiserId the value to set.
     */
    public function setAdvertiserId($advertiserId)
    {
        $this->advertiserId = $advertiserId;
    }

    /**
     * Get companyId.
     *
     * @return companyId.
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * Set companyId.
     *
     * @param companyId the value to set.
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }
}

function isJson($string)
{
    json_decode($string);
    return json_last_error() == JSON_ERROR_NONE;
}
