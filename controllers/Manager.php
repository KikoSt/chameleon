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
        $this->templateCollection->setCompanyId($this->companyId);
        $this->templateCollection->setAdvertiserId($this->advertiserId);

        // $this->templateCollection->addIncludeFilter('categoryId', 167514);
        // $this->templateCollection->addIncludeFilter('categoryId', 167622);
        // $this->templateCollection->addIncludeFilter('advertiserId', 122);

        $this->templateCollection->loadCollectionData();

 //       $this->templateCollection->removeElement(223);
        // $this->templateCollection->removeElement(118);
        // $this->templateCollection->removeElement(223);

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

}
