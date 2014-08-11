<?php

class APIConnector
{
    const REST_API_USERNAME = 'chameleon-api';
    const REST_API_PASSWORD = 'BwHwEpJnIqUgWkOv9YDg';

    private $serviceUrl;
    private $serviceCalls;

    private $advertiserId;
    private $companyId;
    private $bannerTemplateId;

    public function __construct()
    {
        $this->serviceUrl = 'http://bidder.mediadecision.lan:8080/chameleon-0.1/rest';
        $this->serviceCalls = array();
        $this->serviceCalls['getTemplates']    = 'advertiser/{advertiserId}/bannerTemplates';
        $this->serviceCalls['postTemplate']    = 'bannerTemplate';
        $this->serviceCalls['deleteTemplate']  = 'bannerTemplate/{templateId}';
        $this->serviceCalls['getTemplateById'] = 'bannerTemplate/{templateId}';
        $this->serviceCalls['getProductsByCategory'] = 'company/{companyId}/category/{categoryId}/products';
    }

    /**
     * @return array
     */
    public function getMethodList()
    {
        $methodList = array_keys($this->serviceCalls);
        return $methodList;
    }

    /**
     * @param $path
     * @return string
     */
    public function get($path)
    {
        $restCall = $path;
        $response = file_get_contents($restCall);
        return $response;
    }



    /**
     * getProductsByCategory
     *
     * returns all products for a given category for the currently set company and advertiser
     *
     * @param mixed $categoryId
     * @access public
     * @return void
     */
    public function getProductsByCategory($categoryId)
    {
        $resource = $this->serviceUrl . '/' . str_replace('{categoryId}', $categoryId, $this->serviceCalls['getProductsByCategory']);
        $resource = str_replace('{companyId}', $this->companyId, $resource);
        $curl = $this->getCurl($resource, 'GET');

        $curlResponse = curl_exec($curl);
        curl_close($curl);

        $productList = json_decode($curlResponse)->products;

        $products = array();

        foreach($productList AS $product)
        {
            $products[] = $this->populateProduct($product);
        }

        return $products;

    }

    /**
     * @return int
     */
    public function getNumTemplates()
    {
        $templateCount = count($this->getTemplates());
        return $templateCount;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getTemplates()
    {
        if(!isset($this->advertiserId))
        {
            throw new Exception('advertiserId not set');
        }

        $resource = $this->serviceUrl . '/' . str_replace('{advertiserId}', $this->advertiserId, $this->serviceCalls['getTemplates']);
        $curl = $this->getCurl($resource, 'GET');

        $curlResponse = curl_exec($curl);
        curl_close($curl);

        $templateList = json_decode($curlResponse)->bannerTemplateModels;

        $templates = array();

        foreach($templateList AS $template)
        {
            $templates[] = $this->populateBannerTemplate($template);
        }

        return $templates;
    }

    public function getTemplateById($templateId)
    {
        if(!isset($templateId))
        {
            throw new Exception('bannerTemplateId not set');
        }

        $resource = $this->serviceUrl . '/' . str_replace('{templateId}', $templateId, $this->serviceCalls['getTemplateById']);
        $curl = $this->getCurl($resource, 'GET');

        $curlResponse = curl_exec($curl);
        curl_close($curl);

        return $this->populateBannerTemplate(json_decode($curlResponse));
    }

    /**
     * @param $template
     * @return mixed
     */
    public function sendBannerTemplate(BannerTemplateModel $template)
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

    /**
     * @param $templateId
     * @return mixed
     */
    public function deleteBannerTemplate($templateId)
    {
        $resource = $this->serviceUrl . '/' . str_replace('{templateId}', $templateId, $this->serviceCalls['deleteTemplate']);
        $curl = $this->getCurl($resource, 'DELETE');

        $curlResponse = curl_exec($curl);
        curl_close($curl);
        return $curlResponse;
    }

    /**
     * @param $serviceUrl
     * @param $method
     * @return resource
     */
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

    private function populateBannerTemplate($template)
    {
        $bannerTemplateModel = new BannerTemplateModel();
        $bannerTemplateModel->setAdvertiserId($this->advertiserId);
        $bannerTemplateModel->setAuditUserId((int) $template->idAuditUser);
        $bannerTemplateModel->setDescription((string) $template->description);
        $bannerTemplateModel->setName((string) $template->name);
        $bannerTemplateModel->setBannerTemplateId((int) $template->idBannerTemplate);
        $bannerTemplateModel->setParentBannerTemplateId((int) $template->idParentBannerTemplate);
        $bannerTemplateModel->setSvgContent($template->svgContent);

        return $bannerTemplateModel;
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
