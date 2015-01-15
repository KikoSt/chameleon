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
 */
abstract class ElementCollection
{
    private $elements;
    private $properties;
    private $propertyList;

    public function __construct($uidName)
    {
        $this->elements     = array();
        $this->properties   = array();
        $this->propertyList = array();

        $this->uidName = $uidName;

        // TODO: this will NOT be valid for both creatives AND templates ....
        // templates have an ID, creatives a filepath as unique identifier.
        // Then again, we could use the filepath as UID for creatives ...?!
        $this->properties['id']   = array();
        $this->properties['name'] = array();
    }

    public function addElement($element)
    {
        if(array_search($element, $this->elements) === false)
        {
            $index = count($this->elements);
            $this->elements[$index]           = $element;
            $this->properties['id'][$index]   = $element->{'get' . $this->uidName}();
            $this->properties['name'][$index] = $element->getName();
        }
        // adding all required properties to the corresponding dictionaries will be done in the subclasses
    }

    public function removeElement($elementId)
    {
        // check if element with given ID exists and retrieve the key if it does
        // TODO: ... either this or the addElement method will NOT work ... ;)
        if(($key = array_search($elementId, $this->elements)) !== false)
        {
            // remove this element
            unset($this->elements[$key]);
        }
    }
}
