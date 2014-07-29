<?php
$myContainer = new GfxContainer();
$myContainer->setSource('svg/test.svg');
$myContainer->parse();
$myContainer->setTarget('SWF');
$myContainer->render();
$myContainer->setTarget('GIF');
$myContainer->render();

die();
// TODO: this will be _POST later; _REQUEST for development only
$page = $_REQUEST['page'];

$modules = array('test');

if(in_array($page, $modules))
{
    $overview = new Overview();
    echo $overview->create();
}

function __autoload($className)
{
    if(file_exists('libraries/classes/' . $className . '.class.php'))
    {
        require_once('libraries/classes/' . $className . '.class.php');
    }
    else if(file_exists('libraries/interfaces/' . $className . '.interface.php'))
    {
        require_once('libraries/interfaces/' . $className . '.interface.php');
    }
    else if(file_exists('libraries/exception/' . $className . '.exception.php'))
    {
        require_once('libraries/exception/' . $className . '.exception.php');
    }
    else if(file_exists('controllers/' . $className . '.php'))
    {
        require_once('controllers/' . $className . '.php');
    }
}