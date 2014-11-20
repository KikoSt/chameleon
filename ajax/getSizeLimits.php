<?php


$sizeLimits = array();
$gifSizeLimits = array();
$swfSizeLimits = array();

$gifSizeLimits['728x90']  = 100;
$gifSizeLimits['468x60']  =  50;
$gifSizeLimits['234x60']  =  50;
$gifSizeLimits['120x240'] =  50;
$gifSizeLimits['180x150'] =  50;
$gifSizeLimits['300x100'] =  50;
$gifSizeLimits['240x400'] = 100;
$gifSizeLimits['300x250'] =  50;
$gifSizeLimits['336x280'] =  50;
$gifSizeLimits['300x600'] =  50;
$gifSizeLimits['120x600'] =  50;
$gifSizeLimits['160x600'] =  50;
$gifSizeLimits['88x31']   =  25;
$gifSizeLimits['120x60']  =  25;
$gifSizeLimits['125x125'] =  25;
$gifSizeLimits['250x250'] =  25;
$gifSizeLimits['750x300'] = 150;

$swfSizeLimits = array();
$swfSizeLimits['728x90']  = 100;
$swfSizeLimits['468x60']  =  50;
$swfSizeLimits['234x60']  =  50;
$swfSizeLimits['120x240'] =  50;
$swfSizeLimits['180x150'] =  50;
$swfSizeLimits['300x100'] =  50;
$swfSizeLimits['240x400'] = 100;
$swfSizeLimits['300x250'] =  50;
$swfSizeLimits['336x280'] =  50;
$swfSizeLimits['300x600'] =  50;
$swfSizeLimits['120x600'] =  50;
$swfSizeLimits['160x600'] =  50;
$swfSizeLimits['88x31']   =  25;
$swfSizeLimits['120x60']  =  25;
$swfSizeLimits['125x125'] =  25;
$swfSizeLimits['250x250'] =  25;
$swfSizeLimits['750x300'] = 150;

$sizeLimits['swf'] = $swfSizeLimits;
$sizeLimits['gif'] = $gifSizeLimits;

// right now, this file will also be included directly ('require_once') from the controllers; only output the json
// data when called via AJAX
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
{
    echo json_encode($sizeLimits);
}


