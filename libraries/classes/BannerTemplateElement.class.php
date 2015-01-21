<?php
class BannerTemplateElement extends BannerTemplateModel
{
    private $categoryIds;

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
}
