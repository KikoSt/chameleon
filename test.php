<?php

$rest = new RESTCenter();

$template = new BannerTemplateModel('svg/ttest_1.svg');
$template->setDescription('first dummy test thingie');
$template->setIdBannerTemplate(null);
$template->setIdParentBannerTemplate(null);
$template->setName('dummy');
$template->setIdAdvertiser(122);
$template->setIdAuditUser(null);
$template->setSvgContent(simplexml_load_string('<svg><g><title>test</title></g></svg>')->asXml());

$rest->getTemplates(122);
$rest->sendBannerTemplate($template);

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
