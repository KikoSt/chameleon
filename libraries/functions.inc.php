<?php


function getRequestVar($identifier)
{
    if(isJSON($_REQUEST))
    {
        $requestVars = json_decode($_REQUEST);
    } else
    {
        $requestVars = $_REQUEST;
    }

    if(isset($requestVars[$identifier]) && null !== $requestVars[$identifier])
    {
        $returnValue = $requestVars[$identifier];
    }
    else
    {
        throw new Exception($identifier . ' not provided');
    }
    return $returnValue;
}

function isJSON($string)
{
    @json_decode($string);
    if(json_last_error() === JSON_ERROR_NONE)
    {
        return false;
    }
    else
    {
        return true;
    }
}
