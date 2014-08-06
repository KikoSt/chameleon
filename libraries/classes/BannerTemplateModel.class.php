<?php

/**
 * BannerTemplateModel
 *
 * @package
 * @version $id$
 * @copyright 2014 Media Decision GmbH
 * @author Christoph 'Kiko' Starkmann <christoph.starkmann@mediadecision.com>
 * @license Proprietary/Closed Source
 */
class BannerTemplateModel implements JsonSerializable
{
    private $description;
    private $advertiserId;
    private $bannerTemplateId;
    private $parentBannerTemplateId;
    private $auditUserId;
    private $name;
    private $svgContent;

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
        return [
                'description' => $this->getDescription(),
                'idAdvertiser' => $this->getAdvertiserId(),
                'idBannerTemplate' => $this->getBannerTemplateId(),
                'idParentBannerTemplate' => $this->getParentBannerTemplateId(),
                'idAuditUser' => $this->getAuditUserId(),
                'name' => $this->getName(),
                'svgContent' => (string) $this->svgContent
                ];
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
        return $this->advertiserId;
    }

    public function setAdvertiserId($idAdvertiser)
    {
        $this->advertiserId = $idAdvertiser;
    }

    public function getBannerTemplateId()
    {
        return $this->bannerTemplateId;
    }

    public function setBannerTemplateId($idBannerTemplate)
    {
        $this->bannerTemplateId = $idBannerTemplate;
    }

    public function getParentBannerTemplateId()
    {
        return $this->parentBannerTemplateId;
    }

    public function setParentBannerTemplateId($idParentBannerTemplate)
    {
        $this->parentBannerTemplateId = $idParentBannerTemplate;
    }

    public function getAuditUserId()
    {
        return $this->auditUserId;
    }

    public function setAuditUserId($idAuditUser)
    {
        $this->auditUserId = $idAuditUser;
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
}
