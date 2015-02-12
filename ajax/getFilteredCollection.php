<?php
if(!defined('__ROOT__'))
{
    define('__ROOT__', '../');
}

require_once(__ROOT__ . 'libraries/functions.inc.php');
require_once('../libraries/classes/APIConnector.class.php');
require_once('../libraries/classes/BannerTemplateModel.class.php');
$companyId    = getRequestVar('companyId');
$advertiserId = getRequestVar('advertiserId');

$collection = new TemplateCollection();

$collection->setCompanyId($companyId);
$collection->setAdvertiserId($advertiserId);

$filters = getRequestVar('filters');

$collection->loadCollectionData();
$result = array();

foreach($filters AS $property => $values)
{
    $valueList = explode(';', $values);
    foreach($valueList AS $value)
    {
        $collection->addIncludeFilter($property, $value);
    }

}

foreach($collection AS $template)
{
    $element = new StdClass();
    $element->name          = $template->getName();
    $element->width         = $template->getWidth();
    $element->height        = $template->getHeight();
    $element->id            = $template->getId();
    $element->companyId     = $companyId;
    $element->advertiserId  = $advertiserId;
    list($displayWidth, $displayHeight) = calculateRatioSize($template->getWidth(), $template->getHeight(), 350);
    $element->displayWidth  = $displayWidth;
    $element->displayHeight = $displayHeight;
    $element->imgpath = 'output/'  . $companyId . '/' . $advertiserId;
    $element->imgpath .= '/0/preview_' . $template->getId() . '_' . $template->getWidth() . 'x' . $template->getHeight() . '.gif';
    $element->categoryIds   = $template->getCategoryIds();
    $result[] = $element;
}

echo json_encode($result);
