<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 29.07.14
 * Time: 07:21
 */

include('../config/pathconfig.inc.php');

if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}

var_dump($_REQUEST);

$container = new GfxContainer();
$database = new Database();

$templates = $database->fetchTemplatesNext();

foreach($templates as $template)
{
    $container->setCompany($template['company']);
    $container->setAdvertiser($template['advertiser']);
    $container->setId($template['id']);

    $destDir = $container->createDestinationDir();

    $container->setSource($template['template']);
    $container->parse();
    $container->setTarget('GIF');
    $container->setOutputDestination($destDir);
    $container->render();
}

function __autoload($className)
{
    if(file_exists(__ROOT__ . CLASS_DIR . $className . '.class.php'))
    {
        require_once(__ROOT__ . CLASS_DIR . $className . '.class.php');
    }
    else if(file_exists(__ROOT__ . INTERFACE_DIR . $className . '.interface.php'))
    {
        require_once(__ROOT__ . INTERFACE_DIR . $className . '.interface.php');
    }
}