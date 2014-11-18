<?php
/**
 * @param $className
 */
function __autoload($className)
{
    if(file_exists(__ROOT__ . 'libraries/classes/' . $className . '.class.php'))
    {
        require_once(__ROOT__ . 'libraries/classes/' . $className . '.class.php');
    }
    else if(file_exists(__ROOT__ . 'libraries/gdenhancer/' . $className . '.php'))
    {
        require_once(__ROOT__ . 'libraries/gdenhancer/' . $className . '.php');
    }
    else if(file_exists(__ROOT__ . 'libraries/interfaces/' . $className . '.interface.php'))
    {
        require_once(__ROOT__ . 'libraries/interfaces/' . $className . '.interface.php');
    }
    else if(file_exists(__ROOT__ . 'libraries/exception/' . $className . '.exception.php'))
    {
        require_once(__ROOT__ . 'libraries/exception/' . $className . '.exception.php');
    }
    else if(file_exists(__ROOT__ . 'controllers/' . $className . '.php'))
    {
        require_once(__ROOT__ . 'controllers/' . $className . '.php');
    }
}
