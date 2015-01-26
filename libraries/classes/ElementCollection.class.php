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
 * [0] -> element_1
 * [1] -> element_2
 * ...
 * [n-1] -> element_n
 *
 * $properties[<property_name>][0] = [element_x_id]
 * $properties[<property_name>][1] = [element_y_id]
 * $properties[<property_name>][2] = [element_z_id]
 *
 * $properties[<other_property_name>][0] = [element_z_id]
 * $properties[<other_property_name>][1] = [element_x_id]
 * $properties[<other_property_name>][2] = [element_y_id]
 *
 * The company and advertiser id(s) are also an important specific of any collection, so they are stored here, too.
 * Since it's at least imaginable that there are more than one company and/or more than one advertiser, any number of
 * ids can be stored.
 *
 * All three values (advertiserId(s), companyId(s), categoryId(s)) are defining the collection.
 * At least one companyId has to be set before the data can be loaded.
 * The following situations are imaginable:
 *
 * only companyId set:
 * -> get ALL elements for ALL advertisers and ALL categories with the given companyId(s)
 *
 * companyId and advertiserId set:
 * -> get ALL elements for ALL categories with the given companyId(s) and advertiserId(s)
 *
 * companyId, advertiserId and categoryId set:
 * -> get ALL elements for ALL categories with the given companyId(s), advertiserId(s) and categoryId(s)
 *
 */
abstract class ElementCollection implements Iterator
{
    private   $position = 0;
    private   $elements;
    private   $properties;
    private   $propertyList;
    protected $advertiserIds; // ID's of all advertisers contained in this collection
    protected $companyIds; // ID's of all company contained in this collection
    private   $uidName; // name of the unique identifier in the derived class. NOT mutual after object creation!

    private   $filterList; // dictionary: property : value
    private   $sortList;   // dictionary: property : 'asc' or 'desc'

    public function __construct($uidName)
    {
        $this->position = 0;

        $this->advertiserIds = array();
        $this->companyIds    = array();
        $this->categoryIds   = array();

        $this->elements      = array();
        $this->properties    = array();
        $this->propertyList  = array();

        $this->filterList    = array();
        $this->sortList      = array();

        $this->uidName = $uidName;

        $this->properties['uid']   = array();
        $this->properties['name'] = array();
    }

    public function addElement($element)
    {
        if(array_search($element, $this->elements) === false)
        {
            $index = count($this->elements);
            $this->elements[$index]           = $element;
            $this->properties['uid'][$index]  = $element->{'get' . $this->uidName}();
            $this->properties['name'][$index] = $element->getName();
            $this->registerAdvertiserId($element->getAdvertiserId());

            $this->properties['width'][$index]  = $element->getWidth();
            $this->properties['height'][$index] = $element->getHeight();
        }
        // adding all required properties to the corresponding dictionaries will be done in the subclasses
    }

    public function removeElement($elementUid)
    {
        // check if element with given ID exists and retrieve the key if it does
        if(($key = array_search($elementUid, $this->properties['uid'])) !== false)
        {
            $element = $this->elements[$key];
            // remove this element
            unset($this->properties['name'][$key]);
            unset($this->properties['uid'][$key]);

            // REMOVAL here means that the value stored for the respective
            // id(s) (company, advertiser, categories) will be reduced by 1;
            // if the value is zero, the field will be unset completely
            $this->removeCompanyId($this->elements[$key]->getCompanyId());
            $this->removeAdvertiserId($this->elements[$key]->getAdvertiserId());
            $this->removeCategoryIds($this->elements[$key]->getAdvertiserId());
            // remove ALL corresponding category IDs

            // finally, remove the element itself ...
            unset($this->elements[$key]);
            unset($element);
        }
    }

    abstract function loadCollectionData();

    // public accessor methods
    public function getCompanyIds()
    {
        return array_keys($this->companyIds);
    }

    public function getAdvertiserIds()
    {
        return array_keys($this->advertiserIds);
    }

    public function getCategoryIds()
    {
        return array_keys($this->categoryIds);
    }

    public function getCompanyId()
    {
        if(count($this->companyIds) > 1)
        {
            throw new Exception('Multiple companyId\'s set already, no unabiguous reference possible. Please use method getCompanyIds() instead');
        }
        return $this->companyIds[0];
    }

    public function getAdvertiserId()
    {
        if(count($this->advertiserIds) > 1)
        {
            throw new Exception('Multiple advertiserId\'s set already, no unabiguous reference possible. Please use method getAdvertiserIds() instead');
        }
        return $this->advertiserIds[0];
    }

    public function getCategoryId()
    {
        if(count($this->categoryIds) > 1)
        {
            throw new Exception('Multiple categoryId\'s set already, no unabiguous reference possible. Please use method getCategoryIds() instead');
        }
        return $this->categoryIds[0];
    }



    // Those methods should not be calles from externally, so they are protected

    // if there's only one company ID, the company ID for the collection can be used like any single value property, using the
    // set/get accessor methods. As soon as there had been more ID's added using the add method, it's no longer possible to 'set'
    // the id.
    // NOTE: It is highly recommended to use the collection in one way only. Either use set/get and never add multiple id's using
    // the add method, or use add/remove only and the corresponding getCompanyIds method ...
    protected function setCompanyId($companyId)
    {
        if(count($this->companyIds) > 1)
        {
            throw new Exception('Multiple companyId\'s set already, no unabiguous change possible. Please use method addcompanyId() instead');
        }
        else
        {
            $this->companyIds[0] = $companyId;
        }
    }

    protected function setAdvertiserId($advertiserId)
    {
        if(count($this->advertiserIds) > 1)
        {
            throw new Exception('Multiple advertiserId\'s set already, no unabiguous change possible. Please use method addAdvertiserId() instead');
        }
        else
        {
            $this->advertiserIds[0] = $advertiserId;
        }
    }

    protected function setCategoryId($categoryId)
    {
        if(count($this->categoryIds) > 1)
        {
            throw new Exception('Multiple categoryId\'s set already, no unabiguous change possible. Please use method addCategoryId() instead');
        }
        else
        {
            $this->categoryIds[0] = $categoryId;
        }
    }

    // in order to keep track of the id's here, we use the id itself as a key and the corresponding value as a counter,
    // increasing it whenever another element with a given id is added. When removing an element with a given id, the
    // counter is reduced by one, the respective id can be completely removed when the counter hits zero
    protected function registerCompanyId($companyId)
    {
        if(!isset($this->companyIds[$companyId]))
        {
             $this->companyIds[$companyId] = 0;
        }
        $this->companyIds[$companyId]++;
    }

    protected function registerAdvertiserId($advertiserId)
    {
        if(!isset($this->advertiserIds[$advertiserId]))
        {
             $this->advertiserIds[$advertiserId] = 0;
        }
        $this->advertiserIds[$advertiserId]++;
    }

    protected function registerCategoryId($categoryId)
    {
        if(!isset($this->categoryIds[$categoryId]))
        {
             $this->categoryIds[$categoryId] = 0;
        }
        $this->categoryIds[$categoryId]++;
    }
    // END of ID registration methods



    // decrease the corresponding id counter by one. if counter hits zero, remove id entirely from list
    protected function removeCompanyId($companyId)
    {
        if(($key = array_search($companyId, $this->companyIds)) !== false)
        {
            $this->companyIds[$key]--;
            if($this->companyIds[$key] <= 0)
            {
                unset($this->companyIds[$key]);
            }
        }
    }

    protected function removeAdvertiserId($advertiserId)
    {
        if(($key = array_search($advertiserId, $this->advertiserIds)) !== false)
        {
            $this->advertiserIds[$key]--;
            if($this->advertiserIds[$key] <= 0)
            {
                unset($this->advertiserIds[$key]);
            }
        }
    }

    protected function removeCategoryId($categoryId)
    {
        if(($key = array_search($categoryId, $this->categoryIds)) !== false)
        {
            $this->categoryIds[$key]--;
            if($this->categoryIds[$key] <= 0)
            {
                unset($this->categoryIds[$key]);
            }
        }
    }

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
