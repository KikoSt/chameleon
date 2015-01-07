<?php

class Manager extends Controller
{
    private $advertiserId;
    private $companyId;
    private $view;

    public function create()
    {
        $templateIds = $this->getTemplateIds();

        $this->view = '<br /><br /><br /><br /><br /><br /><h1>Manage</h1>';
        $this->view .= $this->getCreatives($templateIds);
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


    private function getCreativesByTemplateId($templateId)
    {
        $result = array();

        // get a list of all sub directories representing categories at first
        // we do NOT want the '..', the '.' and the preview folder
        $exclude = array('..', '.', 'preview');
        $path = __ROOT__ . '/output/' . $this->getCompanyId() . '/' . $this->getAdvertiserId() . '/';
        $list = array_diff(scandir($path), $exclude);

        // now scan all sub dirs for files with the given template ID
        foreach($list AS $subdir)
        {
            $subpath = $path . '/' . $subdir;
            $sublist = array_diff(scandir($subpath), $exclude);
            if(count($sublist) > 0)
            {
                foreach($sublist AS $creative)
                {
                    if(false !== strpos($creative, '' . $templateId) && 0 === strpos($creative, '' . $templateId))
                    {
                        $result[] = $subpath . '/' . $creative;
                    }
                }
            }
        }
        return $result;
    }

    private function getCreatives($templateIds)
    {
        $string = '';

        foreach($templateIds AS $templateId)
        {
            $string .= $templateId . ':<br />';
            $result = $this->getCreativesByTemplateId($templateId);
            foreach($result AS $creative)
            {
                if(false !== strpos($creative, '.gif'))
                {
                    echo basename($creative);
                    echo '<br />';
                    echo '<img src="' . $creative . '" /><br />';
                    echo '<br />';
                }
            }

        }

        return $string;
    }
}
