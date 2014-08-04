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
$connector = new APIConnector();

//$container->setId($_REQUEST['id']);
//$template = $connector->getTemplateById();

var_dump($_REQUEST['id']);


//create a new svg with the given request parameters

$container->changeElementValue($_POST);
$container->getSvg();

//$container->setTarget('GIF');
//$container->setOutputDestination($container->createDestinationDir());
//$container->calculateOutputDir();
//$container->render();





