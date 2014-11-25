<?php
/**
 *  remove all (live) preview images; there are two cases when we do this:
 *  - unsaved changes are being "discarded" by leaving the editor without saving
 *    (there IS a security dialog!)
 *  - a new, changed preview is generated; In this case, the live preview images
 *    will no longer be up-to-date
 */

include('../config/pathconfig.inc.php');
require_once('../Bootstrap.php');

if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}

require_once(__ROOT__ . 'libraries/functions.inc.php');

$companyId      = getRequestVar('companyId');
$advertiserId   = getRequestVar('advertiserId');
$templateId     = getRequestVar('templateId');

$mode           = getRequestVar('mode');

if($mode === 'removeEditorPreview')
{
    $filepath = __ROOT__ . 'output/' . $companyId . '/' . $advertiserId . '/0/';
    $pattern  = 'preview_' . $templateId . '*';
    $files = glob($filepath . $pattern);
    foreach($files AS $file)
    {
        $success = unlink($file);
    }
}
else if($mode == 'removeLivePreview')
{
}

echo  json_encode('Done!');
