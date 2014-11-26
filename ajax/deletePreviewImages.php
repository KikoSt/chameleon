<?php
/**
 *  remove all (live) preview images; there are two cases when we do this:
 *  - unsaved changes are being "discarded" by leaving the editor without saving
 *    (there IS a security dialog!)
 *  - a new, changed preview is generated; In this case, the live preview images
 *    will no longer be up-to-date
 */

require_once('../config/pathconfig.inc.php');
require_once('../Bootstrap.php');

if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}

require_once(__ROOT__ . 'libraries/functions.inc.php');

$modes = array('removeEditorPreview', 'removeLivePreview');

$companyId      = (int)getRequestVar('companyId');
$advertiserId   = (int)getRequestVar('advertiserId');
$templateId     = (int)getRequestVar('templateId');

$mode           = getRequestVar('mode');

if(!in_array($mode, $modes))
{
    return false;
}

$filepath = __ROOT__ . 'output/' . $companyId . '/' . $advertiserId . '/';

if($mode === 'removeEditorPreview')
{
    // remove the editor preview image for this template
    $filepath .= '0/';
    $pattern   = 'preview_' . $templateId . '*';
}
else if($mode == 'removeLivePreview')
{
    // remove all live preview images for this template
    $filepath .= 'preview/' . $templateId . '/';
    $pattern   = '*';
}

$files     = glob($filepath . $pattern);
$success   = true;

if(count($files) > 0)
{
    foreach($files AS $file)
    {
        $success = $success && unlink($file);
    }
}

echo json_encode($success);
