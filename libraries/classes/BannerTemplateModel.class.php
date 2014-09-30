<?php

/**
 * BannerTemplateModel  TODO add description
 *
 * @package
 * @version $id$
 * @copyright 2014 Media Decision GmbH
 * @author Christoph 'Kiko' Starkmann <christoph.starkmann@mediadecision.com>
 * @author Thomas Hummel <thomas.hummel@mediadecision.com>
 * @license Proprietary/Closed Source
 */
class BannerTemplateModel implements JsonSerializable
{
    private $description;
    private $idAdvertiser;
    private $idBannerTemplate;
    private $idParentBannerTemplate;
    private $idAuditUser;
    private $name;
    private $dimX;
    private $dimY;
    private $idGroup;
    private $svgContent;
    private $dateCreate;
    private $dateModified;
    private $categorySubscriptions;

    public function __construct($source=null)
    {
        if($source !== null)
        {
            $this->setSource($source);
            $this->readSvgFromFile();
        }
    }

    public function setSource($source)
    {
        if(file_exists($source))
        {
            $this->source = $source;
        }
    }

    // ?? needed here ??
    public function saveAsSvg($filepath)
    {
        if(is_writable($filepath))
        {
            if(!$handle = fopen($filepath, "r+"))
            {
                throw new FileException();
            }
            else
            {
                fwrite($handle, $this->getSvgAsString());
            }
        }
    }

    public function getSvgAsString()
    {
        return $this->svg->asXML();
    }

    // ??
    private function readSvgFromFile()
    {
        $this->svgContent = simplexml_load_file($this->source)->asXml();
    }


    public function jsonSerialize()
    {
        return array(
                'description' => $this->getDescription(),
                'idAdvertiser' => $this->getAdvertiserId(),
                'idBannerTemplate' => $this->getBannerTemplateId(),
                'idParentBannerTemplate' => $this->getParentBannerTemplateId(),
                'idAuditUser' => $this->getAuditUserId(),
                'name' => $this->getName(),
                'dimX' => $this->getDimX(),
                'dimY' => $this->getDimY(),
                'idGroup' => $this->getGroupId(),
                'svgContent' => (string) $this->svgContent
               );
    }

    // ??
    public function __toString()
    {
        // TODO: There's more than only the svg to serialize
        return $this->svg->asXML();
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getAdvertiserId()
    {
        return $this->idAdvertiser;
    }

    public function setAdvertiserId($idAdvertiser)
    {
        $this->idAdvertiser = $idAdvertiser;
    }

    public function getBannerTemplateId()
    {
        return $this->idBannerTemplate;
    }

    public function setBannerTemplateId($bannerTemplateId)
    {
        $this->idBannerTemplate = $bannerTemplateId;
    }

    public function getParentBannerTemplateId()
    {
        return $this->idParentBannerTemplate;
    }

    public function setParentBannerTemplateId($parentTemplateId)
    {
        $this->idParentBannerTemplate = $parentTemplateId;
    }

    public function getAuditUserId()
    {
        return $this->idAuditUser;
    }

    public function setAuditUserId($auditUserId)
    {
        $this->idAuditUser = $auditUserId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getSvgContent()
    {
        return $this->svgContent;
    }

    public function setSvgContent($svgContent)
    {
        $this->svgContent = $svgContent;
    }

    public function getDimY()
    {
        return $this->dimY;
    }

    public function setDimY($dimY)
    {
        $this->dimY = (int) $dimY;
    }

    public function getDimX()
    {
        return $this->dimX;
    }

    public function setDimX($dimX)
    {
        $this->dimX = (int) $dimX;
    }

    public function getGroupId()
    {
        return $this->idGroup;
    }

    public function setGroupId($groupId)
    {
        $this->idGroup = $groupId;
    }

    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;
    }

    public function getDateModified()
    {
        return $this->dateModified;
    }

    public function setDateModified($dateModified)
    {
        $this->dateModified = $dateModified;
    }

    public function getCategorySubscriptions()
    {
        return $this->categorySubscriptions;
    }

    public function setCategorySubscriptions($categorySubscriptions)
    {
        $this->categorySubscriptions = $categorySubscriptions;
    }
}
