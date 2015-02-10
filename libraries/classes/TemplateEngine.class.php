<?php
/**
 * TemplateEngine
 *
 * basic template "engine"
 *
 * @category  Class
 * @package   Chameleon
 * @author    Christoph 'Kiko' Starkmann <christoph.starkmann@mediadecision.com>
 * @copyright 2014 Media Decision GmbH
 * @license   Proprietary/Closed Source
 * @version   Release: Chameleon_1.1
 * @link      somelink
 */
class TemplateEngine
{
    private $pagesAvailable;

    protected $template;
    protected $variables = array();

    protected $previews;
    protected $message;
    protected $page;
    protected $id;
    protected $width;
    protected $height;

    public function __construct($template)
    {
        $this->pagesAvailable = array('overview', 'editor', 'manage');
        $this->template = $template;
        $this->page = 'overview'; // fallback

        $this->previews = array();
    }

    public function __toString()
    {
        extract($this->variables);

        chdir(dirname($this->template));
        ob_start();

        include basename($this->template);

        return ob_get_clean();
    }

    // TODO: hard coding for now, since general rework of the editor might make general changes necessary, so
    // avoiding to spend too much time on it for now
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }

    public function getCompanyId()
    {
        return $this->companyId;
    }

    public function setAdvertiserId($advertiserId)
    {
        $this->advertiserId = $advertiserId;
    }

    public function getAdvertiserId()
    {
        return $this->advertiserId;
    }

    // ELEMENTS
    public function setElements($elements)
    {
        $this->elements = $elements;
    }

    public function getElements()
    {
        return $this->elements;
    }

    // PAGE
    public function getPage()
    {
        return $this->page;
    }

    public function setPage($page)
    {
        if(!in_array($page, $this->pagesAvailable))
        {
            $page = 'overview';
        }
        $this->page = $page;

    }

    // PREVIEWS
    public function setPreviews($previews)
    {
        $this->previews = $previews;
    }

    public function getPreviews()
    {
        return $this->previews;
    }

    // ID
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    // WIDTH
    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function getWidth()
    {
        return $this->variables['width'];
    }

    // HEIGHT
    public function setHeight($height)
    {
        if(is_numeric($height))
        {
            $this->height = $height;
            return true;
        }
        else
        {
            return false;
        }
    }

    public function getHeight()
    {
        return $this->variables['height'];
    }

    // MESSAGE
    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
