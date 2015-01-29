<?php

/**
 * ElementCollection
 *
 * @package
 * @version $id$
 * @copyright 2014 Media Decision GmbH
 * @author Christoph 'Kiko' Starkmann <christoph.starkmann@mediadecision.com>
 * @license Proprietary/Closed Source
 *
 * class providing a collection of elements that can be filtered and sorted based on different properties;
 * Those properties are seperately stored in different indexes for quick and easy computations and retrieval
 *
 * the elements list is not ordered at all, containing all elements with a numeric key that is used as index in
 * the respective property lists:
 *
 * elements = array(0 => 134, 1 => 5, 2 => 117, 3 => 205);
 *
 * template[0][companyId] = 100;
 * template[0][advertiserId] = 4711;
 * template[0][categoryIds] = array(1, 2, 3, 4);
 *
 * template[1][companyId] = 100;
 * template[1][advertiserId] = 0815;
 * template[1][categoryIds] = array(2, 3, 5);
 *
 * template[2][companyId] = 100;
 * template[2][advertiserId] = 4711;
 * template[2][categoryIds] = array(3, 4, 5);
 *
 * template[3][companyId] = 100;
 * template[3][advertiserId] = 9911;
 * template[3][categoryIds] = array(4);
 *
 * =>
 *
 * this->companyIds[100] = array(0, 1, 2, 3);
 * this->advertiserIds[4711] = array(0, 2);
 * this->categoryIds[1] = array(0);
 * this->categoryIds[2] = array(0, 1);
 * this->categoryIds[3] = array(0, 1, 2);
 * this->categoryIds[4] = array(0, 2, 3);
 *
 * OR
 *
 * this->properties[companyId][100] = array(0, 1, 2, 3);
 * this->properties[advertiserId][4711] = array(0, 2);
 * this->properties[categoryId][1] = array(0);
 * this->properties[categoryId][2] = array(0, 1);
 * this->properties[categoryId][3] = array(0, 1, 2);
 * this->properties[categoryId][4] = array(0, 2, 3);
 *
 */
abstract class ElementCollection implements Iterator
{
    protected $companyId;
    protected $advertiserId;

    protected $position = 0;
    protected $elements;
    protected $properties;
    protected $propertyList;
    protected $uidName;       // name of the unique identifier in the derived class. NOT mutual after object creation!

    protected $filterList;    // dictionary: property : value
    protected $sortList;      // dictionary: property : 'asc' or 'desc'

    public function __construct($uidName)
    {
        $this->position = 0;

        $this->elements      = array();
        $this->properties    = array();
        $this->propertyList  = array();

        $this->filterList    = array();
        $this->sortList      = array();

        $this->uidName = $uidName;
    }

    public function addFilter($propertyName, $value)
    {
        $this->filterList[$propertyName] = $value;
    }


    public function addElement($element)
    {
        if(array_search($element, $this->elements) === false)
        {
            $index = count($this->elements);
            $this->elements[$index] = $element;
            $this->registerProperty('uid',          $element->{'get' . $this->uidName}(), $index);
            $this->registerProperty('name',         $element->getName(),                  $index);
            $this->registerProperty('advertiserId', $element->getAdvertiserId(),          $index);
            $this->registerProperty('companyId',    $element->getCompanyId(),             $index);
            $this->registerProperty('width',        $element->getWidth(),                 $index);
            $this->registerProperty('height',       $element->getHeight(),                $index);
        }
        // adding all required properties to the corresponding dictionaries will be done in the subclasses
        return $index;
    }

    public function removeElement($elementId)
    {
        // get internal id from elementId
        $elementIndex = $this->properties['uid'][$elementId][0];
        foreach($this->properties AS $propName => $value)
        {
            unset($value);
            $this->unregisterProperty($propName, $elementIndex);
        }
        unset($this->elements[$elementIndex]);
    }

    public function unregisterProperty($property, $elementIndex)
    {
        foreach($this->properties[$property] AS $key => $indexArray)
        {
            // $deleteResults = array_keys($this->properties[$property][$key], $elementIndex);
            foreach($indexArray AS $foundkey => $value)
            {
                if($value == $elementIndex)
                {
                    unset($this->properties[$property][$key][$foundkey]);
                    if(count($this->properties[$property][$key]) < 1)
                    {
                        unset($this->properties[$property][$key]);
                    }
                }
            }
        }
    }

    public function registerProperty($property, $value, $index)
    {
        if(!isset($this->properties[$property]) || !is_array($this->properties[$property]))
        {
            $this->properties[$property] = array();
        }
        $this->properties[$property][$value][] = $index;
    }

    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }

    public function setAdvertiserId($advertiserId)
    {
        $this->advertiserId = $advertiserId;
    }

    public function getCompanyId()
    {
        return $this->companyId;
    }

    public function getAdvertiserId()
    {
        return $this->advertiserId;
    }



    public function getCompanyIds()
    {
        return array_keys($this->properties['companyId']);
    }

    public function getAdvertiserIds()
    {
        return array_keys($this->properties['advertiserId']);
    }

    public function getCategoryIds()
    {
        return array_keys($this->properties['categoryId']);
    }




//    public function removeElement($elementUid)
//    {
//        // check if element with given ID exists and retrieve the key if it does
//        if(($key = array_search($elementUid, $this->properties['uid'])) !== false)
//        {
//            $element = $this->elements[$key];
//            // remove this element
//            unset($this->properties['name'][$key]);
//            unset($this->properties['uid'][$key]);
//
//            // REMOVAL here means that the value stored for the respective
//            // id(s) (company, advertiser, categories) will be reduced by 1;
//            // if the value is zero, the field will be unset completely
//            $this->removeCompanyId($this->elements[$key]->getCompanyId());
//            $this->removeAdvertiserId($this->elements[$key]->getAdvertiserId());
//            $this->removeCategoryIds($this->elements[$key]->getAdvertiserId());
//            // remove ALL corresponding category IDs
//
//            // finally, remove the element itself ...
//            unset($this->elements[$key]);
//            unset($element);
//        }
//    }
//
//    abstract function loadCollectionData();
//
//    // public accessor methods
//    public function getCompanyIds()
//    {
//        return array_keys($this->companyIds);
//    }
//
//    public function getAdvertiserIds()
//    {
//        return array_keys($this->advertiserIds);
//    }
//
//    public function getCategoryIds()
//    {
//        return array_keys($this->categoryIds);
//    }
//
//    public function getCompanyId()
//    {
//        if(count($this->companyIds) > 1)
//        {
//            throw new Exception('Multiple companyId\'s set already, no unabiguous reference possible. Please use method getCompanyIds() instead');
//        }
//        reset($this->companyIds);
//        $companyId = key($this->companyIds);
//        return $companyId;
//    }
//
//    public function getAdvertiserId()
//    {
//        if(count($this->advertiserIds) > 1)
//        {
//            throw new Exception('Multiple advertiserId\'s set already, no unabiguous reference possible. Please use method getAdvertiserIds() instead');
//        }
//        reset($this->advertiserIds);
//        $advertiserId = key($this->advertiserIds);
//        return $advertiserId;
//    }
//
//    public function getCategoryId()
//    {
//        if(count($this->categoryIds) > 1)
//        {
//            throw new Exception('Multiple categoryId\'s set already, no unabiguous reference possible. Please use method getCategoryIds() instead');
//        }
//        reset($this->categoryIds);
//        $categoryId = key($this->categoryIds);
//        return $categoryId;
//    }
//
//
//
//    // if there's only one company ID, the company ID for the collection can be used like any single value property, using the
//    // set/get accessor methods. As soon as there had been more ID's added using the add method, it's no longer possible to 'set'
//    // the id.
//    // NOTE: It is highly recommended to use the collection in one way only. Either use set/get and never add multiple id's using
//    // the add method, or use add/remove only and the corresponding getCompanyIds method ...
//    public function setCompanyId($companyId)
//    {
//        if(count($this->companyIds) > 1)
//        {
//            throw new Exception('Multiple companyId\'s set already, no unabiguous change possible. Please use method addcompanyId() instead');
//        }
//        else
//        {
//            $this->registerCompanyId($companyId);
//        }
//    }
//
//    public function setAdvertiserId($advertiserId)
//    {
//        if(count($this->advertiserIds) > 1)
//        {
//            throw new Exception('Multiple advertiserId\'s set already, no unabiguous change possible. Please use method addAdvertiserId() instead');
//        }
//        else
//        {
//            $this->registerAdvertiserId($advertiserId);
//        }
//    }
//
//    public function setCategoryId($categoryId)
//    {
//        if(count($this->categoryIds) > 1)
//        {
//            throw new Exception('Multiple categoryId\'s set already, no unabiguous change possible. Please use method addCategoryId() instead');
//        }
//        else
//        {
//            $this->registerCategoryId($categoryId);
//        }
//    }
//
//    // in order to keep track of the id's here, we use the id itself as a key and the corresponding value as a counter,
//    // increasing it whenever another element with a given id is added. When removing an element with a given id, the
//    // counter is reduced by one, the respective id can be completely removed when the counter hits zero
//    protected function registerCompanyId($companyId)
//    {
//        if(!isset($this->companyIds[$companyId]))
//        {
//             $this->companyIds[$companyId] = 0;
//        }
//        $this->companyIds[$companyId]++;
//    }
//
//    protected function registerAdvertiserId($advertiserId)
//    {
//        if(!isset($this->advertiserIds[$advertiserId]))
//        {
//             $this->advertiserIds[$advertiserId] = 0;
//        }
//        $this->advertiserIds[$advertiserId]++;
//    }
//
//    protected function registerCategoryId($categoryId)
//    {
//        if(!isset($this->categoryIds[$categoryId]))
//        {
//             $this->categoryIds[$categoryId] = 0;
//        }
//        $this->categoryIds[$categoryId]++;
//    }
//    // END of ID registration methods
//
//
//
//    // decrease the corresponding id counter by one. if counter hits zero, remove id entirely from list
//    protected function removeCompanyId($companyId)
//    {
//        if(($key = array_search($companyId, $this->companyIds)) !== false)
//        {
//            $this->companyIds[$key]--;
//            if($this->companyIds[$key] <= 0)
//            {
//                unset($this->companyIds[$key]);
//            }
//        }
//    }
//
//    protected function removeAdvertiserId($advertiserId)
//    {
//        if(($key = array_search($advertiserId, $this->advertiserIds)) !== false)
//        {
//            $this->advertiserIds[$key]--;
//            if($this->advertiserIds[$key] <= 0)
//            {
//                unset($this->advertiserIds[$key]);
//            }
//        }
//    }
//
//    protected function removeCategoryId($categoryId)
//    {
//        if(($key = array_search($categoryId, $this->categoryIds)) !== false)
//        {
//            $this->categoryIds[$key]--;
//            if($this->categoryIds[$key] <= 0)
//            {
//                unset($this->categoryIds[$key]);
//            }
//        }
//    }

    /* ****************** *
     *  ITERATOR methods  *
     * ****************** */

    function current()
    {
        return $this->elements[$this->position];
    }

    function key()
    {
        return $this->position;
    }

    function next()
    {
        ++$this->position;
    }

    function rewind()
    {
        $this->position = 0;
    }

    function valid()
    {
        return isset($this->elements[$this->position]);
    }
}
