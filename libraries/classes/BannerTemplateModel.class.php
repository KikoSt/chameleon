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
class BannerTemplateModel
{
    private $description;
    private $idAdvertiser;
    private $idBannerTemplate;
    private $idParentBannerTemplate;
    private $idAuditUser;
    private $name;
    private $svgContent;

    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct($source=null)
    {
        if($source !== null)
        {
            // $this->setSource($source);
            // $this->readSvgFromFile();
        }
    }

    public function setSource($source)
    {
        if(file_exists($source))
        {
            $this->source = $source;
        }
    }

    public function getSvgAsString()
    {
        return $this->svg->asXML();
    }

    private function readSvgFromFile()
    {
        $this->svg = simplexml_load_file($this->source);
    }




    public function __toString()
    {
        // TODO: There's more than only the svg to serialize
        return $this->svg->asXML();
    }

    /**
     * Get description.
     *
     * @return description.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description.
     *
     * @param description the value to set.
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get idAdvertiser.
     *
     * @return idAdvertiser.
     */
    public function getIdAdvertiser()
    {
        return $this->idAdvertiser;
    }

    /**
     * Set idAdvertiser.
     *
     * @param idAdvertiser the value to set.
     */
    public function setIdAdvertiser($idAdvertiser)
    {
        $this->idAdvertiser = $idAdvertiser;
    }

    /**
     * Get idBannerTemplate.
     *
     * @return idBannerTemplate.
     */
    public function getIdBannerTemplate()
    {
        return $this->idBannerTemplate;
    }

    /**
     * Set idBannerTemplate.
     *
     * @param idBannerTemplate the value to set.
     */
    public function setIdBannerTemplate($idBannerTemplate)
    {
        $this->idBannerTemplate = $idBannerTemplate;
    }

    /**
     * Get idParentBannerTemplate.
     *
     * @return idParentBannerTemplate.
     */
    public function getIdParentBannerTemplate()
    {
        return $this->idParentBannerTemplate;
    }

    /**
     * Set idParentBannerTemplate.
     *
     * @param idParentBannerTemplate the value to set.
     */
    public function setIdParentBannerTemplate($idParentBannerTemplate)
    {
        $this->idParentBannerTemplate = $idParentBannerTemplate;
    }

    /**
     * Get idAuditUser.
     *
     * @return idAuditUser.
     */
    public function getIdAuditUser()
    {
        return $this->idAuditUser;
    }

    /**
     * Set idAuditUser.
     *
     * @param idAuditUser the value to set.
     */
    public function setIdAuditUser($idAuditUser)
    {
        $this->idAuditUser = $idAuditUser;
    }

    /**
     * Get name.
     *
     * @return name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param name the value to set.
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get svgContent.
     *
     * @return svgContent.
     */
    public function getSvgContent()
    {
        return $this->svgContent;
    }

    /**
     * Set svgContent.
     *
     * @param svgContent the value to set.
     */
    public function setSvgContent($svgContent)
    {
        $this->svgContent = $svgContent;
    }
}
