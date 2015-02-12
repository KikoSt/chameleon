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
        $this->view = $this->setLayout('views/manager_dyn.phtml')->getView();

        // $this->view->creativeDirectory = $this->getCreatives($templateIds);

        $this->view->setCompanyId($this->companyId);
        $this->view->setAdvertiserId($this->advertiserId);
        $this->view->setPage('manage');

        $this->templateCollection = new TemplateCollection();
        $this->templateCollection->setCompanyId($this->companyId);
        $this->templateCollection->setAdvertiserId($this->advertiserId);

//        $this->templateCollection->addIncludeFilter('width', 750);
        $this->templateCollection->addIncludeFilter('categoryId', 167622);
        $this->templateCollection->addIncludeFilter('categoryId', 167736);
        // $this->templateCollection->addIncludeFilter('categoryId', 169192);
        // $this->templateCollection->addIncludeFilter('advertiserId', 122);

        $this->templateCollection->loadCollectionData();

 //       $this->templateCollection->removeElement(223);
        // $this->templateCollection->removeElement(118);
        // $this->templateCollection->removeElement(223);

        $this->view->setElements($this->templateCollection);
        $this->view->setMessage('Guten Tag, guten Tag!');
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
