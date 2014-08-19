<?php

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
        $this->next = $next;
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
