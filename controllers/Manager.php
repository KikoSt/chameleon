<?php

class Manager extends Controller
{
    private $advertiserId;
    private $companyId;
    private $view;
    private $templateCollection;
    // private $creativeCollection;

    public function create()
    {
        $this->view = $this->setLayout('views/manager.phtml')->getView();

        // $this->view->creativeDirectory = $this->getCreatives($templateIds);
        $this->view->companyId         = $this->companyId;
        $this->view->advertiserId      = $this->advertiserId;
        $this->view->page              = 'manage';

        $this->templateCollection = new TemplateCollection();

        $connector = new APIConnector();
        $connector->setAdvertiserId($this->advertiserId);
        $connector->setCompanyId($this->companyId);

        $templateIds = $this->getTemplateIds();
        foreach($templateIds AS $templateId)
        {
            $template = $connector->getTemplateById($templateId);
            $this->templateCollection->addElement($template);
        }
        $this->view->elements = $this->templateCollection;
        $this->view->message = 'Guten Tag, guten Tag!';
    }

    public function display()
    {
        echo $this->view;
    }
    public function setAdvertiserId($advertiserId)
    {
        $this->advertiserId = $advertiserId;
    }

    public function getAdvertiserId()
    {
        return $this->advertiserId;
    }

    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }

    public function getCompanyId()
    {
        return $this->companyId;
    }

    public function setAuditUserId($auditUserId)
    {
        $this->auditUserId = $auditUserId;
    }

    public function getAuditUserId()
    {
        return $this->auditUserId;
    }

    private function getTemplateIds()
    {
        $connector = new APIConnector();
        $connector->setAdvertiserId($this->getAdvertiserId());
        $connector->setCompanyId($this->getCompanyId());

        $templateIds = array();

        $templates = $connector->getTemplates();

        foreach($templates AS $template)
        {
            $templateIds[] = $template->getId();
        }

        return $templateIds;
    }



    // TODO: method to retrieve creatives using filter:
    // - templateId
    // - categoryId



    private function getCreativesFiltered($filterTemplateId = null, $filterCategoryId = null)
    {
        $filterCategoryId = 167574;
        $result = array();

        // get a list of all sub directories representing categories at first
        // we do NOT want the '..', the '.' and the preview folder
        $exclude = array('..', '.', 'preview');
        $path = __ROOT__ . 'output/' . $this->getCompanyId() . '/' . $this->getAdvertiserId();

        if(null !== $categoryId)
        {
            // only scan the given category subdir if any one is given
            $categoryList = array($filterCategoryId);
        }
        else
        {
            // or all except the excluded directories
            $categoryList = array_diff(scandir($path), $exclude);
        }

        // now scan all subdirs for files with the given template ID
        foreach($categoryList AS $categoryId)
        {
            $subpath = $path . '/' . $categoryId;
            $sublist = array_diff(scandir($subpath), $exclude);
            if(count($sublist) > 0)
            {
                foreach($sublist AS $creative)
                {
                    if(false !== strpos($creative, '' . $filterTemplateId) && 0 === strpos($creative, '' . $filterTemplateId))
                    {
                        $curCreative = new StdClass();
                        $curCreative->filePath     = $subpath . '/' . $creative;
                        $curCreative->categoryId   = $categoryId;
                        $curCreative->templateId   = $filterTemplateId;
                        $curCreative->companyId    = $this->getCompanyId();
                        $curCreative->advertiserId = $this->getAdvertiserId();
                        $result[] = $curCreative;
                    }
                }
            }
        }
        return $result;
    }

    private function getCreatives($templateIds)
    {
        $result = array();

        foreach($templateIds AS $templateId)
        {
            $creatives = $this->getCreativesFiltered($templateId);
            foreach($creatives AS $creative)
            {
                if(false !== strpos($creative->filePath, '.gif'))
                {
                    $result[] = $creative;
                }
            }

        }
        return $result;
    }
}
