<?php
/**
 * Comment here
 *
 * User: thomas.hummel@mediadecision.com
 * Date: 17/07/2014
 * Time: 07:30
 */
require_once('GfxComponent.class.php');

class GfxContainer
{
    protected $elements;
    private $id;
    private $title;
    private $target;
    private $source;
    private $canvasWidth;
    private $canvasHeight;
    private $canvas;
    private $outputName; // default name if not set!
    private $outputDir;
    private $companyId;
    private $advertiserId;
    private $editorOptions;

    private $allowedTargets;

    public function __construct()
    {
        $this->allowedTargets = array('SWF', 'GIF');
    }

    public function parse()
    {
        $svg = new SimpleXMLElement(file_get_contents($this->source));

        $main = $svg->children();

        $this->setCanvasWidth((float) $svg->attributes()->width);
        $this->setCanvasHeight((float) $svg->attributes()->height);

        foreach($main->children() AS $child)
        {
            if($child->getName() === "title")
            {
                $this->setTitle($child->getName);
            } else {
                $gfxInstance = $this->getGfxInstance($child->getName());
                if($gfxInstance)
                {
                    $gfxInstance->create($child);
                    $this->addElement($gfxInstance);
                }
                unset($gfxInstance);
            }
        }
    }

    public function getElements()
    {
        return $this->elements;
    }

    public function addElement($element)
    {
        if(is_a($element, 'GfxComponent'))
        {
            $this->elements[] = $element;

            //give element to partial
        }
        else
        {
            throw new InvalidArgumentException();
        }
    }

    /**
     * getOutputFilename
     *
     * @access private
     * @return string
     */
    private function getOutputFilename()
    {
        if($this->getOutputName() != '')
        {
            $filename = $this->getOutputName();
        }
        else
        {
            if('' == $this->getCompanyId() || '' == $this->getAdvertiserId())
            {
                throw new Exception('Company or advertiser id not set');
            }
            if('' == $this->getId())
            {
                throw new Exception('Template id not set');
            }

            $filename = $this->getCompanyId();
            $filename .= '_' . $this->getAdvertiserId();
            $filename .= '_' . $this->getCanvasHeight();
            $filename .= 'x' . $this->getCanvasWidth();
            $filename .= '_' . $this->getId();
        }

        $filename .= '.' . strtolower($this->getTarget());

        return $filename;
    }

    /**
     * calculateOutputDir
     * if OUTPUT_DIR = 'output/', company id = 4 and advertiser id = 122, the path to the resulting ads is
     *
     * output/4/122/
     *
     * @access private
     * @return string outputDir full path name to output dir based on company and advertiser ids
     */
    private function calculateOutputDir()
    {
        if('' == $this->getCompanyId() || '' == $this->getAdvertiserId())
        {
            throw new Exception('Company or Advertiser ID missing');
        }
        // if there is a trailing / in OUTPUT_DIR, remove it, then assemble all parts to output directory path
        $parts = array(rtrim(OUTPUT_DIR, '/'), $this->getCompanyId(), $this->getAdvertiserId());

        $outputDir = implode('/', $parts);

        return $outputDir;
    }

    /**
     * createOutputDir
     *
     * physically creates the output dir
     * TODO: what to return?
     *
     * @access public
     * @return void
     */
    public function createOutputDir()
    {
        if('' === $this->getOutputDir())
        {
            throw new Exception('Output dir not set');
        }
        else
        {
            $this->setOutputDir($this->calculateOutputDir());
        }

        $dir = $this->getOutputDir();

        if(!file_exists($dir))
        {
            // set the current umask to 0777
            $old = umask(0);
            if(!mkdir($dir, 0777, true))
            {
                throw new Exception('Could not create directory ' . $dir);
            }
            // reset umask
            umask($old);
        }

        return $dir;
    }

    /**
     * setOutputDir
     *
     * @param string $outputDir
     * @access public
     * @return void
     */
    public function setOutputDir($outputDir)
    {
        $this->outputDir = $outputDir;
    }


    /**
     * getOutputDir
     *
     * @access public
     * @return string
     */
    public function getOutputDir()
    {
        if(!isset($this->outputDir) || '' == $this->outputDir)
        {
            $this->setOutputDir($this->calculateOutputDir());
        }
        return $this->outputDir;
    }

    /**
     * render
     *
     * wrapper function to start the rendering process; depending on the currently set target,
     * either GIF or SWF rendering will be triggered
     *
     * @access public
     * @return void
     */
    public function render()
    {
        $this->createOutputDir();

        if($this->target === 'SWF')
        {
            $this->renderSWF();
        }
        else if($this->target === 'GIF')
        {
            $this->renderGIF();
        }
    }

    /**
     * renderSWF
     *
     * create the swf file based on the current template
     *
     * @access private
     * @return void
     */
    private function renderSWF()
    { $swf = new SWFMovie(); $swf->setDimension($this->getCanvasWidth(), $this->getCanvasHeight()); $swf->setFrames(30);
        $swf->setRate(10);
        $swf->setBackground(0, 0, 0);

        foreach($this->elements AS $element)
        {
            if(is_a($element, 'GfxComponent'))
            {
                $element->renderSWF($swf);
            }
        }
        $swf->save($this->getOutputDir() . '/' . $this->getOutputFilename());
        chmod($this->getOutputDir() . '/' . $this->getOutputFilename(), 0777);
    }

    /**
     * renderGIF
     *
     * create the gif file based on the current template
     *
     * @access private
     * @return void
     */
    private function renderGIF()
    {
        $this->setCanvas(imagecreatetruecolor($this->getCanvasWidth(), $this->getCanvasHeight()));

        foreach($this->elements as $element)
        {
            $updatedCanvas = $element->renderGif($this->getCanvas());
        }

        $this->setCanvas($updatedCanvas);

        imagegif($updatedCanvas, $this->getOutputDir() . '/' . $this->getOutputFilename());
        chmod($this->getOutputDir() . '/' . $this->getOutputFilename(), 0777);
    }

    /**
     * getOptionsForEditor
     *
     * ???
     *
     * @access public
     * @return void
     */
    public function getOptionsForEditor()
    {
        return $this->editorOptions;
    }


    /**
     * getGfxInstance
     *
     * creates and returns a new instance of any existing GFXComponent subtypes
     * This is a helper method for easy svg parsing
     *
     * @param mixed $type
     * @access private
     * @return void
     */
    private function getGfxInstance($type)
    {
        // easily extendable, just add new types here
        $componentTypes = array('rect' => 'GfxRectangle',
                                'text' => 'GfxText',
                                'image' => 'GfxImage',
                                'ellipse' => 'GfxEllipse');
        if (array_key_exists($type, $componentTypes))
        {
            // create instance of requested class based on the above mapping
            $gfxInstance = new $componentTypes[$type]();
        }
        else
        {
            return false;
        }
        return $gfxInstance;
    }

    /**
     * changeElementValue
     *
     * ???
     *
     * @param mixed $formData
     * @access public
     * @return void
     */
    public function changeElementValue($formData)
    {
        //iterate all svg elements
        foreach($this->getElements() as $element)
        {
            $id = $element->getId();

            //iterate form data
            foreach($formData as $key => $value)
            {
                $cleansedKey = explode('#', $key);
                $param = $cleansedKey[1];

                // form data containing the current element found?
                if(strcasecmp($cleansedKey[0], $id) == 0)
                {
                    $func="set" . ucwords($param);

                    if($param === "fill" || $param === "stroke")
                    {
                        $color = new GfxColor($value);
                        $element->$func($color);
                    }
                    else
                    {
                        $element->$func($value);
                    }
                }
            }
        }
    }

    /* **************************************
              Accessors
    ***************************************** */
    public function setTarget($target)
    {
        if(!in_array($target, $this->allowedTargets)) {
            throw new Exception('Unknown format ' . $target . ' for GfxContainer');
        } else {
            $this->target = $target;
        }
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function getCanvasWidth()
    {
        return $this->canvasWidth;
    }

    public function getCanvasHeight()
    {
        return $this->canvasHeight;
    }
    public function setCanvasWidth($newCanvasWidth)
    {
        $this->canvasWidth = $newCanvasWidth;
    }

    public function setCanvasHeight($newCanvasHeight)
    {
        $this->canvasHeight = $newCanvasHeight;
    }

    public function setCanvasSize($newCanvasWidth, $newCanvasHeight)
    {
        $this->canvasWidth = $newCanvasWidth;
        $this->canvasHeight = $newCanvasHeight;
    }

    /**
     * getAdvertiserId
     *
     * @access public
     * @return int
     */
    public function getAdvertiserId()
    {
        return $this->advertiserId;
    }

    /**
     * setAdvertiserId
     *
     * @param int $advertiserId
     * @access public
     * @return void
     */
    public function setAdvertiserId($advertiserId)
    {
        $this->advertiserId = $advertiserId;
    }

    /**
     * getCompanyId
     *
     * @access public
     * @return int $companyId
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * setCompanyId
     *
     * @param int $companyId
     * @access public
     * @return void
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * Get title.
     *
     * @return title.
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title.
     *
     * @param title the value to set.
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * getCanvas
     *
     * @access public
     * @return object $canvas the canvas the ad will be rendered into
     */
    public function getCanvas()
    {
        return $this->canvas;
    }

    /**
     * setCanvas
     *
     * @param object $canvas
     * @access public
     * @return void
     */
    public function setCanvas($canvas)
    {
        $this->canvas = $canvas;
    }

    /**
     * setOutputName
     *
     * @param string $outputName
     * @access public
     * @return void
     */
    public function setOutputName($outputName)
    {
        $this->outputName = $outputName;
    }

    /**
     * getOutputName
     *
     * @access public
     * @return string
     */
    public function getOutputName()
    {
        return $this->outputName;
    }

    /**
     * setSource
     *
     * @param mixed $source
     * @access public
     * @return void
     */
    public function setSource($source)
    {
        $source = SVG_DIR . $source;

        if(file_exists($source))
        {
            $this->source = $source;
        }
        else
        {
            throw new FileNotFoundException('File '.$source.' not found !');
        }
    }

    /**
     * setId
     *
     * @param string $id
     * @access public
     * @return void
     */
    public function setId($id)
    {
        $this->id =$id;
    }

    public function getId()
    {
        return $this->id;
    }






    // Magic Methods
    /**
     * __toString
     *
     * @access public
     * @return string a textual representation of the container
     */
    public function __toString()
    {
        $string = '';
        foreach($this->elements AS $element) {
            $string .= print_r($element, true);
        }
        return $string;
    }
}
