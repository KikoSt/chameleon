<?php

/**
 * TemplateCollection
 *
 * Handle a collection of templates, retrieve them filtered, sorted etc.
 *
 *
 * @package
 * @version $id$
 * @copyright 2014 Media Decision GmbH
 * @author Christoph 'Kiko' Starkmann <christoph.starkmann@mediadecision.com>
 * @license Proprietary/Closed Source
 */
class TemplateCollection extends ElementCollection
{
    public function __construct()
    {
        parent::__construct('id');
        $this->filterNameMappings = array(
                                            'categoryId' => 'categoryIds',
                                            'companyId'  => 'companyId'
                                            );
    }



    public function loadCollectionData()
    {
        define('EXCLUDE', true);

        $connector = new APIConnector();

        $connector->setAdvertiserId($this->getAdvertiserId());
        $connector->setCompanyId($this->getCompanyId());

        $templateIds = $this->getTemplateIds();
        foreach($templateIds AS $templateId)
        {
            $template = $connector->getTemplateById($templateId);
            $template->setCompanyId($this->getCompanyId());
            $this->addElement($template);
        }

        foreach($this->elements AS $key => $element)
        {
            $discard = EXCLUDE;
            foreach($this->filterList AS $key => $value)
            {
                $key = $this->filterNameMappings[$key];
                $func = 'get' . ucfirst($key);
                if(method_exists($element, $func))
                {
                    $filterProp = $element->$func();
                    if(is_array($filterProp) && in_array($value, $filterProp))
                    {
                        echo 'Filtered value ' . $key . ' = ' . $value . " found\n";
                        $discard = !EXCLUDE;
                    }
                    else
                    {
                        $discard = EXCLUDE;
                    }
                }
                else
                {
                    echo 'Oh!';
                }
                if($discard)
                {
                    unset($this->elements[$key]);
                }
            }
        }
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

    public function addElement($element)
    {
        $index = parent::addElement($element);

        foreach($element->getCategoryIds() as $categoryId)
        {
            $this->registerProperty('categoryId', $categoryId, $index);
        }
    }

    public function removeElement($elementUid)
    {
        if(($key = array_search($elementUid, $this->properties['uid'])) !== false)
        {
            $element = $this->elements[$key];

            $this->advertiserIds[$this->elements->getAdvertiserId()]--;
            if($this->advertiserIds[$this->elements->getAdvertiserId()] === 0)
            {
                unset($this->advertiserIds[$this->elements->getAdvertiserId()]);
            }

            unset($element);
        }

        parent::removeElement($elementUid);
    }
}
