<?php
if(!isset($_SERVER['HTTP_HOST']))
{
    // called locally via console!
    $basedir = '/var/www/';
}
else
{
    $basedir = $_SERVER['DOCUMENT_ROOT'] . '/';
}

define('ROOT_DIR', $basedir . 'chameleon/');

define('ASSET_DIR', ROOT_DIR . 'assets');
define('OUTPUT_DIR', ROOT_DIR . 'output');
define('AJAX_DIR', ROOT_DIR . 'ajax');
define('CONFIG_DIR', ROOT_DIR . 'config');
define('CONTROLLER_DIR', ROOT_DIR . 'controllers');
define('FONT_FDB_DIR', ROOT_DIR . 'fonts/fdb');
define('FONT_TTF_DIR', ROOT_DIR . 'fonts/ttf');
define('IMAGE_DIR', ROOT_DIR . 'images');
define('CLASS_DIR', ROOT_DIR . 'libraries/classes');
define('INTERFACE_DIR', ROOT_DIR . 'libraries/interfaces');
define('EXCEPTION_DIR', ROOT_DIR . 'libraries/exception');
define('SVG_DIR', ROOT_DIR . 'svg');
define('LOG_DIR', ROOT_DIR . 'logs');

define('GIFPROTO', ROOT_DIR . 'assets/gifProto');

define('IMGCACHE_DIR', ROOT_DIR . 'imgcache');

if(isset($_SERVER['SERVER_NAME']))
{
    define('BASE_DIR', 'http://' . $_SERVER['SERVER_NAME'] . '/chameleon');
}

define('PREVIEW_NAME', 'preview_<templateId>_<width>x<height>');
