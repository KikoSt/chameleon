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
 * id's can be stored.
 */
abstract class ElementCollection implements Iterator
{
    private $position = 0;
    private $elements;
    private $properties;
    private $propertyList;
    private $advertiserIds; // ID's of all advertisers contained in this collection
    private $companyIds; // ID's of all company contained in this collection
    private $uidName; // name of the unique identifier in the derived class. NOT mutual after object creation!

    public function __construct($uidName)
    {
        $this->position = 0;

        $this->advertiserIds = array();
        $this->companyIds    = array();

        $this->elements      = array();
        $this->properties    = array();
        $this->propertyList  = array();

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
            $this->addCompanyId(0); // $element->getCompanyId());
            $this->addAdvertiserId($element->getAdvertiserId());
        }
        // adding all required properties to the corresponding dictionaries will be done in the subclasses
    }

    public function removeElement($elementUid)
    {
        // check if element with given ID exists and retrieve the key if it does
        if(($key = array_search($elementUid, $this->properties['uid'])) !== false)
        {
            // remove this element
            unset($this->elements[$key]);
            unset($this->properties['name'][$key]);
            unset($this->properties['uid'][$key]);
        }
    }


    // if there's only one company ID, the company ID for the collection can be used like any single value property, using the
    // set/get accessor methods. As soon as there had been more ID's added using the add method, it's no longer possible to 'set'
    // the id.
    // NOTE: It is highly recommended to use the collection in one way only. Either use set/get and never add multiple id's using
    // the add method, or use add/remove only and the corresponding getCompanyIds method ...
    public function setCompanyId($companyId)
    {
        if(count($this->companyIds) > 0)
        {
            throw new Exception('Multiple companyId\'s set already, no unabiguous change possible. Please use method addcompanyId() instead');
        }
        else
        {
            $this->companyIds[0] = $companyId;
        }
    }

    public function setAdvertiserId($advertiserId)
    {
        if(count($this->advertiserIds) > 0)
        {
            throw new Exception('Multiple advertiserId\'s set already, no unabiguous change possible. Please use method addAdvertiserId() instead');
        }
        else
        {
            $this->advertiserIds[0] = $advertiserId;
        }
    }

    public function addCompanyId($companyId)
    {
        if(array_search($companyId, $this->companyIds) === false)
        {
            $this->companyIds[] = $companyId;
        }
    }

    public function addAdvertiserId($advertiserId)
    {
        if(array_search($advertiserId, $this->advertiserIds) === false)
        {
            $this->advertiserIds[] = $advertiserId;
        }
    }

    public function removeCompanyId($companyId)
    {
        if(($key = array_search($companyId, $this->companyIds)) !== false)
        {
            unset($this->companyIds[$key]);
        }
    }

    public function removeAdvertiserId($advertiserId)
    {
        if(($key = array_search($advertiserId, $this->advertiserIds)) !== false)
        {
            unset($this->advertiserIds[$key]);
        }
    }

    public function getCompanyIds()
    {
        return $this->companyIds;
    }

    public function getAdvertiserIds()
    {
        return $this->advertiserIds;
    }

    public function getAdvertiserId()
    {
        if(count($this->advertiserIds) > 0)
        {
            throw new Exception('Multiple advertiserId\'s set already, no unabiguous change possible. Please use method getAdvertiserId() instead');
        }
        else
        {
            return $this->advertiserIds[0];
        }
    }

    public function getCompanyId()
    {
        if(count($this->companyId) > 0)
        {
            throw new Exception('Multiple companyId\'s set already, no unabiguous change possible. Please use method getCompanyId() instead');
        }
        else
        {
            return $this->companyIds[0];
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
