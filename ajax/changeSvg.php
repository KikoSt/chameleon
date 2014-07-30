<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 29.07.14
 * Time: 07:21
 */

include('../config/pathconfig.inc.php');
require_once('../Bootstrap.php');


if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}

$container = new GfxContainer();
$database = new Database();

$template = $database->fetchTemplateById($_REQUEST['id']);

$container->setCompany($template['company']);
$container->setAdvertiser($template['advertiser']);
$container->setId($template['id']);

$container->setSource($template['template']);
$container->parse();

$container->changeElementValue($_POST);

$container->setTarget('GIF');
$container->setOutputDestination($container->createDestinationDir());
$container->render();





