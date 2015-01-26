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
    }

    public function addElement($element)
    {
        parent::addElement($element);

        // TODO: where do we get this info from?
        $element->setCompanyId(170);
        $this->registerCompanyId(170); // $element->getCompanyId());

        // we cannot use any other "simple" array operations here since we're counting the number of
        // elements with each category id to be able to decide whether or not we can remove a category
        // id completely from the list here in the collection
        foreach($element->getCategoryIds() as $categoryId)
        {
            $this->registerCategoryId($categoryId);
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
