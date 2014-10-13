<?php

if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}

require_once(__ROOT__ . 'libraries/functions.inc.php');
//include('../config/pathconfig.inc.php');
require('../libraries/classes/APIConnector.class.php');
require('../libraries/classes/BannerTemplateModel.class.php');

if(!isset($_REQUEST['templateId']))
{
    return false;
}
else
{
    $templateId = (int) $_REQUEST['templateId'];
}
$connector = new APIConnector();
$template = $connector->getTemplateById($templateId);

$subscriptions = $template->getCategorySubscriptions();

foreach($subscriptions as $curCategory)
{
    $id    = $curCategory->idCategory;
    $value = $curCategory->categoryName;

    if($curCategory->userStatus === 'ACTIVE')
    {
?>
<div id="row_<?php echo $id;?>" class="row">
    <div class="col-md-10">
        <?php echo $value;?>
    </div>
    <button id="<?php echo $id;?>" class="removeCategory" type="button" style="color:#000000">
        <span class="glyphicon glyphicon-minus"></span>
    </button>
</div>
<?php
    }
}
?>
