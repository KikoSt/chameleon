<?php

class Creator
{
    private $threadId;
    private $companyId;
    private $advertiserId;
    private $userId;

    public function __construct($threadId)
    {
        $this->threadId = $threadId;
    }

