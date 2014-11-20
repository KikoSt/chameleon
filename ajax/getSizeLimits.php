<?php


$sizeLimits = array();
$sizeLimits['728x90']  = 100;
$sizeLimits['468x60']  =  50;
$sizeLimits['234x60']  =  50;
$sizeLimits['120x240'] =  50;
$sizeLimits['180x150'] =  50;
$sizeLimits['300x100'] =  50;
$sizeLimits['240x400'] = 100;
$sizeLimits['300x250'] =  50;
$sizeLimits['336x280'] =  50;
$sizeLimits['300x600'] =  50;
$sizeLimits['120x600'] =  50;
$sizeLimits['160x600'] =  50;
$sizeLimits['88x31']   =  25;
$sizeLimits['120x60']  =  25;
$sizeLimits['125x125'] =  25;
$sizeLimits['250x250'] =  25;
$sizeLimits['750x300'] = 200;

// right now, this file will also be included directly ('require_once') from the controllers; only output the json
// data when called via AJAX
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
{
    echo json_encode($sizeLimits);
}


