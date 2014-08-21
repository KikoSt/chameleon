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













class ListNode
{
    private $next;
    private $key;
    private $data;

    public function __construct($key, $data=null)
    {
        $this->key  = $key;
        $this->data = $data;
        $this->next = NULL;
    }

    public function setNext(ListNode $next=null)
    {
        $this->next = &$next;
    }

    public function getNext()
    {
        return $this->next;
    }

    public function insertAfter($key, $data)
    {
        $node = new ListNode($key);
        $node->setData($data);
        $node->setNext($this->getNext());
        $this->setNext($node);

        return $node;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

     /**
      * Get key.
      *
      * @return key.
      */
     public function getKey()
     {
         return $this->key;
     }

     /**
      * Set key.
      *
      * @param key the value to set.
      */
     public function setKey($key)
     {
         $this->key = $key;
     }
}


class ListIterator
{
    private $list;
    private $previous;
    private $current;
    private $next;

    public function __construct(LinkedList $list)
    {
        $this->list = $list;
        $this->current = $list->getHead();
    }

    public function previous()
    {
        // find previous!
        $node = $this->list->getHead();
        if($node->getKey() !== $this->current->getKey())
        {
            while($node->getNext()->getKey() !== $this->current->getKey())
            {
                $node = $node->getNext();
            }
        }
        $this->previous = $node;
        $this->next = $this->current;
        $this->current = $this->previous;

    }

    public function current()
    {
        return $this->current;
    }

    public function next()
    {
        var_dump($this->current);

        $this->previous = $this->current;
        $this->current = $this->next;
        $this->next = $this->current->getNext();

        return $this->current;
    }

    public function hasPrevious()
    {
        if($this->previous() !== NULL)
        {
            $hasPrevious = true;
        }
        else
        {
            $hasPrevious = false;
        }
        return $hasPrevious;
    }

    public function hasNext()
    {
        if($this->next() !== NULL)
        {
            $hasNext = true;
        }
        else
        {
            $hasNext = false;
        }
        return $hasNext;
    }

    public function rewind()
    {
        $this->current = $this->list->getHead();
        $this->next = $this->current->getNext();
    }

}
