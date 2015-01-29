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

    protected $includeFilterList;    // dictionary: property : value
    protected $excludeFilterList;    // dictionary: property : value
    protected $sortList;      // dictionary: property : 'asc' or 'desc'

    public function __construct($uidName)
    {
        $this->position = 0;

        $this->elements          = array();
        $this->properties        = array();
        $this->propertyList      = array();

        $this->includeFilterList = array();
        $this->excludeFilterList = array();
        $this->sortList          = array();

        $this->uidName = $uidName;
    }

    public function addIncludeFilter($propertyName, $value)
    {
        if(!array_key_exists($propertyName, $this->includeFilterList))
        {
            $this->includeFilterList[$propertyName] = array();
        }
        $this->includeFilterList[$propertyName][] = $value;
    }

    public function addExcludeFilter($propertyName, $value)
    {
        if(!array_key_exists($propertyName, $this->excludeFilterList))
        {
            $this->excludeFilterList[$propertyName][] = $value;
        }
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



    // Wrapper functions for main properties
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

    // TODO: temporary!
    public function getElements()
    {
        $result = array();
        // apply filters
        // $filterList =
        // apply sorting

        return $result;
    }



    /* ****************** *
     *  ITERATOR methods  *
     * ****************** */

    function current()
    {
        $valids = array();
        // // include filter specify that the data displayed includes ONLY information specified in the filter
        if(count($this->includeFilterList) > 0)
        {
            foreach($this->includeFilterList AS $key => $filterValue)
            {
                // now we need the index for $filterProperty where $value = $key
        //         // echo 'Key = ' . $key . ', ' . print_r($filterValue, 1) . "\n\n<br />";
                $elementList = $this->properties[$key][$filterValue[0]];
                $valids = $valids + array_values($elementList);
            }
        }
        else
        {
            $valids = array_keys($this->properties['uid']);
            $valids = array_values($this->properties['uid']);

            echo '<span style="font-size:.7em">';
            foreach($valids AS $validId)
            {
                echo '{{ ' . $validId[0] . ' }}';
            }
            echo '<br />';
            echo '</span>';

        }

        if(array_search($this->position, $valids))
        {
            return $this->elements[$this->position];
        }
        else
        {
            $this->position++;
            $this->current();
        }
    }

    function key()
    {
        return $this->position;
    }

    function next()
    {
        do
        {
            ++$this->position;
        } while(!isset($this->elements[$this->position]) && $this->position <= max(array_keys($this->elements)));
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
