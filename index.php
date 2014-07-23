<?php
// TODO: this will be _POST later; _REQUEST for development only
$page = $_REQUEST['page'];

$modules = array('overview', 'editor');

if(in_array($page, $modules))
{
    switch($page)
    {
        case 'overview':
        {
            $overview = new Overview();
            echo $overview->create();
            break;
        }
        case "editor":
        {

        }
    }

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