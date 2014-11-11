<?php

require_once('../Bootstrap.php');
include('../config/pathconfig.inc.php');

iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");

if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}

// // get ini settings
// try
// {
//     $iniSettings = parse_ini_file('../generate.ini');
// }
// catch(Exception $e)
// {
//     echo 'Ini file not found, exiting';
//     exit(1);
// }
//
// // set adequat error level
// try
// {
//     error_reporting($iniSettings['reporting_level']);
// }
// catch(Exception $e)
// {
//     echo $e->getMessage();
//     error_reporting(E_ALL);
// }

$categoryId = (int) $argv[3];

error_reporting(E_ALL);
$generator = new CMEOGenerator($argv);
$generator->setTemplates(array(96));
echo $categoryId . "\n\n";
$generator->setCategories($categoryId);
echo 'generating' . "\n";
$generator->generate();

