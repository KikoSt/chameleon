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

        $this->addCompanyId(0); // $element->getCompanyId());

        foreach($element->getCategorySubscriptions() AS $category)
        {
            if($category->userStatus === 'ACTIVE')
            {
                $this->addCategoryId($category->idCategory);
            }
        }
    }
}
