<?php


function getRequestVar($identifier)
{
    if(isset($_REQUEST[$identifier]) && null !== $_REQUEST[$identifier])
    {
        $returnValue = $_REQUEST[$identifier];
    }
    else
    {
        throw new Exception($identifier . ' not provided');
    }

    return $returnValue;
}

