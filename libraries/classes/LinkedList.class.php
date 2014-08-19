<?php

class LinkedList
{
    private $head;
    private $nodeCount;

    public function __construct()
    {
        $this->head = NULL;
        $this->nodeCount = 0;
    }

    public function isEmpty()
    {
        return($this->head === NULL);
    }

    public function insertFirst($key, $data)
    {
        $node = new ListNode($key);
        $node->setData($data);
        if($this->head !== NULL)
        {
            $node->next = $this->head;
        }
        $this->head = $node;
        $this->nodeCount++;
    }

    public function insertBefore($nodeKey, $key, $data)
    {
        // TODO: not working correctly; mainly a problem in case $nodeKey referes to the HEAD
        $node = $this->getHead();
        if($node->getKey() !== $nodeKey)
        {
            while($node->getNext()->getKey() !== $nodeKey)
            {
                $node = $node->getNext();
            }
        }
        $newNode = new ListNode($key, $data);
        $newNode->setNext($node->getNext());
        $node->setNext($newNode);
    }

    public function insertAfter($nodeKey, $key, $data)
    {
        $node = $this->findNode($nodeKey);

        $newNode = new ListNode($key, $data);
        $newNode->setNext($node->getNext());
        $node->setNext($newNode);
    }

    public function deleteNode($nodeKey)
    {
        $node = $this->getHead();
        while($node->getNext()->getKey() !== $nodeKey)
        {
            $node = $node->getNext();
        }
        $deleteNode = $node->getNext();
        $node->setNext($deleteNode->getNext());
        unset($deleteNode);
    }

    public function findNode($key)
    {
        $node = $this->getHead();
        while($node->getKey() !== $key)
        {
            $node = $node->getNext();
        }
        return $node;
    }

    public function getHead()
    {
        return $this->head;
    }

    public function __toString()
    {
        $string = 'LinkedList: ';
        $node = $this->getHead();
        do
        {
            $string .= $node->getKey() . ' --> ';
        }
        while(($node = $node->getNext()) !== NULL);

        return $string;
    }
}
