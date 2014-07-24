<body>
<head>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
</head>
<html>
<?php
    session_start();

    $myIndex = new Index();

    $redirect = $myIndex->getRedirect($_REQUEST['page']);

    echo $redirect->create();
?>
</html>
</body>
<?php
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