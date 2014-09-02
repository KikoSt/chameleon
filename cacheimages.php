<?php

require_once('Bootstrap.php');
include('config/pathconfig.inc.php');

iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");

if(!defined('__ROOT__'))
{
    define('__ROOT__', './');
}

$companyId    = (int) $argv[1];
$advertiserId = (int) $argv[2];
$categoryId   = (int) $argv[3];
$auditUserId  = (int) $argv[4];

echo $companyId . ' ' . $advertiserId . ' ' . $categoryId . "\n";

// path to imageCache should be identical to the path used elsewhere:
//<companyId>/<advertiserId>/<categoryId>/

$connector = new APIConnector();
$connector->setAdvertiserId($advertiserId);
$connector->setCompanyId($companyId);
$connector->setAuditUserId($auditUserId);

$products    = $connector->getProductsByCategory($categoryId);

$cacheDir = $companyId . '/' . $advertiserId . '/' . $categoryId;

if(!is_dir(IMGCACHE_DIR . '/' . $cacheDir))
{
    // set the current umask to 0777
    $old = umask(0);
    if(!mkdir(IMGCACHE_DIR . '/' . $cacheDir, 0777, true))
    {
        throw new Exception('Could not create directory ' . IMGCACHE_DIR . '/' . $cacheDir);
    }
    // reset umask
    umask($old);
}

foreach($products AS $product)
{
    echo $product->getImageUrl();
    echo "\n";

    $fileurl = $product->getImageUrl();
    $newname = $fileurl;
    $newname = urlencode($newname);
    // $newname = preg_replace('/[\.:\/]/', '_', $newname);
    echo $newname . "\n";
    copy($fileurl, IMGCACHE_DIR . '/' . $cacheDir . '/' . $newname);
}
