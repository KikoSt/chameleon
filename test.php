<?php

$advertiserId = 122;
$userId = 14;
$counting = array('First', 'Second', 'Third', 'Forth', 'Fifth');

$connector = new APIConnector();

// for($i = 1; $i<6; $i++)
// {
//     $template = new BannerTemplateModel('svg/ttest_' . $i . '.svg');
//     $template->setDescription($counting[$i-1] . ' dummy test template');
//     $template->setIdBannerTemplate(null);
//     $template->setIdParentBannerTemplate(null);
//     $template->setName('dummy' . $i);
//     $template->setIdAdvertiser($advertiserId);
//     $template->setIdAuditUser($userId);
//
//     echo 'Advertiser ' . $advertiserId . ' has ' . $connector->getNumTemplates($advertiserId) . ' templates.' . "\n";
//
//     // $result = $connector->deleteBannerTemplate(95);
//    $connector->sendBannerTemplate($template);
// }


$templates = $connector->getTemplates($advertiserId);

foreach($templates AS $template)
{
    echo '===============================================' . "\n";
    echo $template->getName() . ' (id=' . $template->getIdBannerTemplate() . ')' . "\n";
    echo '\'' . $template->getDescription() . '\'' . "\n";
    echo '-----------------------------------------------' . "\n";
    echo $template->getSvgContent() . "\n";
    echo '===============================================' . "\n";
}

echo 'Advertiser ' . $advertiserId . ' has ' . $connector->getNumTemplates($advertiserId) . ' templates.' . "\n";

die();





$connector->getTemplates($advertiserId);
//$connector->sendBannerTemplate($template);
// $result = $connector->deleteBannerTemplate(78);

var_dump($result);

var_dump($template);
$connector->getTemplates($advertiserId);
die();

// $connector->getMethodList();
// die();

$connector->getTemplates($advertiserId);
var_dump($template);
//$connector->sendBannerTemplate($template);
$connector->deleteBannerTemplate(81);
$connector->getTemplates($advertiserId);

exit(0);

for($i=1; $i<6;$i++)
{
    $myContainer = new GfxContainer();
    $myContainer->setSource('svg/ttest_' . $i . '.svg');
    $myContainer->setOutputName('output_' . $i);
    $myContainer->parse();
    $myContainer->setTarget('SWF');
    $myContainer->render();
    $myContainer->setTarget('GIF');
    $myContainer->render();
}

$myContainer = new GfxContainer();
$myContainer->setSource('svg/ttest_crit.svg');
$myContainer->setOutputName('output_crit');
$myContainer->parse();
$myContainer->setTarget('SWF');
$myContainer->render();
$myContainer->setTarget('GIF');
$myContainer->render();

function __autoload($className) {
    if(file_exists('libraries/classes/' . $className . '.class.php')) {
        require_once('libraries/classes/' . $className . '.class.php');
    } else if(file_exists('libraries/interfaces/' . $className . '.interface.php')) {
        require_once('libraries/interfaces/' . $className . '.interface.php');
    }
}
