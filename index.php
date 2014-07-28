<body>
<head>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php $_SERVER['SERVER_NAME']?>colorpicker/js/bootstrap-colorpicker.js"></script>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php $_SERVER['SERVER_NAME']?>colorpicker/css/colorpicker.css">
</head>
<html>
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container" style="width: 100%">
            <div class="navbar-header" style="height: 60px;">
                <img class="navbar-brand" src="images/MediaDecision_logo_small.png" style="height: 60px;"/>

            </div>
            <p class="navbar-text"><nobr><h3><?php echo ucwords($_REQUEST['page']);?></h3></nobr></p>
        </div>
    </div>
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