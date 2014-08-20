<?php

require_once('Bootstrap.php');
include('config/pathconfig.inc.php');

iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");

if(!defined('__ROOT__'))
{
    define('__ROOT__', './');
}

$advertiserId = 122;
$companyId    = 170;
$userId       = 14;

$productCategories = array(7, 10, 11881, 11887, 11890, 11893, 11899, 11902, 11908, 11911, 11917, 11923, 11929, 11932, 11935, 11941, 11944, 11947, 11950, 11956, 11959, 11962, 11968, 11971, 11974, 11977, 11980, 11986, 11989, 11992, 11995, 11998, 12001, 12004, 12007, 12013, 12016, 12019, 12022, 12025, 12028, 12031, 12040, 12043, 12049, 12052, 12055, 12058, 12061, 12067, 12073, 12079, 12082, 12085, 12088, 12091, 12094, 12097, 12100, 12103, 12106, 12112, 12115, 12118, 12124, 12127, 12130, 12133, 12136, 12139, 12142, 12145, 12148, 12151, 12157, 12160, 12163, 12166, 12178, 12181, 12184, 12187, 12190, 12193, 12196, 12199, 12202, 12208, 12211, 12214, 12217, 12220, 12223, 12226, 12229, 12232, 12235, 12238, 12241, 12244, 12247, 12250, 12253, 12256, 12259, 12262, 12265, 12268, 12271, 12274, 12277, 12280, 12283, 12286, 12288, 12291, 12294, 12297, 12300, 12303);

$productCategories = array(7);

$connector = new APIConnector();

$connector->setAdvertiserId($advertiserId);
$connector->setCompanyId($companyId);

$basepath = OUTPUT_DIR . '/' . $companyId . '/' . $advertiserId;

$filelist = array();
$count = 0;

foreach($productCategories AS $categoryId)
{
    $filepath = $basepath . '/' . $categoryId . '/';
    if($dirhandle = opendir($filepath))
    {
        while(($file = readdir($dirhandle)) !== false)
        {
            if(is_file($filepath . '/' . $file))
            {
                $count++;
                echo '(' . $count . ') ';
                $filetype = preg_replace('/^.*./', '', $file);
                $filename = preg_replace('/.[^.]*$/', '', $file);
                list($categoryId, $name, $productId, $dimensions) = explode('_', $filename);
                list($height, $width) = explode('x', $dimensions);
                $curCreative = new CreativeModel();
                $curCreative->setFilename($file);
                $curCreative->setWidth($width);
                $curCreative->setHeight($height);
                $curCreative->setProductId($productId);
                $curCreative->setSWfpath($filepath . '/' . $filename . '.swf');
                $curCreative->setGifPath($filepath . '/' . $filename . '.swf');
                $curCreative->prepareTransmission();
                // var_dump($curCreative);
                // $connector->sendCreative($curCreative);
                $filelist[] = $curCreative;
            }
        }
        $connector->sendCreatives($filelist);
        echo $count . ' creatives sent!';
        // var_dump($fileList);
    }
}
