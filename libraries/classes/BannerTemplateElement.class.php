<?php
class BannerTemplateElement extends BannerTemplateModel
{
    private $categoryIds;
    private $companyId;

    public function __construct($source=null)
    {
        parent::__construct($source);
        $this->categoryIds = array();
    }

    public function setCategorySubscriptions($subscriptions)
    {
        parent::setCategorySubscriptions($subscriptions);

        foreach($subscriptions AS $category)
        {
            if($category->userStatus === 'ACTIVE')
            {
                $this->addCategoryId($category->idCategory);
            }
        }
    }

    // the BannerTemplateModel doesn't store a company ID, so we are doing this here
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }

    public function getCompanyId()
    {
        return $this->companyId;
    }

    protected function addCategoryId($categoryId)
    {
        if(array_search($categoryId, $this->categoryIds) === false)
        {
            $this->categoryIds[] = $categoryId;
        }
    }

    public function getCategoryIds()
    {
        return $this->categoryIds;
    }

    public function getWidth()
    {
        return $this->getDimX();
    }

    public function getHeight()
    {
        return $this->getDimY();
    }
}
