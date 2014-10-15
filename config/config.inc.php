<?php

require_once('Bootstrap.php');

if(!defined('__ROOT__'))
{
    define('__ROOT__', './');
}
require_once(__ROOT__ . 'libraries/functions.inc.php');

include(__ROOT__ . 'config/pathconfig.inc.php');
include(__ROOT__ . 'config/apiconfig.inc.php');
include(__ROOT__ . 'config/bannersizes.inc.php');
include(__ROOT__ . 'config/fontconfig.inc.php');

define('PREVIEW_NAME', 'preview_<templateId>_<width>x<height>');
