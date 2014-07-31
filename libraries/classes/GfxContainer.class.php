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
    private $id;
    protected $elements;
    private $target;
    private $sSource;
    private $canvasWidth;
    private $canvasHeight;
    private $canvas;
    private $outputName; // default name if not set!
    private $destination;
    private $company;
    private $advertiser;
    private $editorOptions;


    private $allowedTargets;

    public function __construct()
    {
//        $this->elements = array();
        $this->allowedTargets = array('SWF', 'GIF');
    }

    /**
     * @return mixed
     */
    public function getCanvas()
    {
        return $this->canvas;
    }

    /**
     * @param mixed $canvas
     */
    public function setCanvas($canvas)
    {
        $this->canvas = $canvas;
    }

    public function setOutputName($outputName)
    {
        $this->outputName = $outputName;
    }

    public function getOutputName()
    {
        return $this->outputName;
    }

    public function setSource($sSource)
    {
        $sSource = SVG_DIR . $sSource;

        if(file_exists($sSource))
        {
            $this->sSource = $sSource;
        }
        else
        {
            throw new FileNotFoundException('File '.$sSource.' not found !');
        }
    }

    public function parse()
    {
        $svg = new SimpleXMLElement(file_get_contents($this->sSource));

        $main = $svg->children();

        $this->setCanvasWidth((float) $svg->attributes()->width);
        $this->setCanvasHeight((float) $svg->attributes()->height);

        foreach($main->children() AS $child) {
            $gfxInstance = $this->getGfxInstance($child->getName());
            if($gfxInstance) {
                $gfxInstance->create($child);
                $this->addElement($gfxInstance);
            }
            unset($gfxInstance);
        }
    }

    public function setId($sId)
    {
        $this->id =$sId;
    }

    public function getId()
    {
        return $this->id;
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
        if(null !== $this->getOutputName() && $this->getOutputName() !== '')
        {
            $filename = $this->getOutputName();
        }
        else
        {
            $filename = $this->getCompany();
            $filename .= '_' . $this->getAdvertiser();
            $filename .= '_' . $this->getCanvasHeight();
            $filename .= 'x' . $this->getCanvasWidth();
            $filename .= '_' . time();
            $filename .= '_' . $this->getId();
        }

        $filename .= '.' . strtolower($this->getTarget());

        return $filename;
    }

    public function setOutputDestination($destination)
    {
        $this->destination = $destination;
    }


    private function getOutputDestination()
    {
        $destination = $this->destination . $this->getOutputFilename();
        return $destination;
    }

    public function render()
    {
        if($this->target === 'SWF')
        {
            $this->renderSWF();
        }
        else if($this->target === 'GIF')
        {
            $this->renderGIF();
        }
    }

    private function renderSWF()
    {
        $swf = new SWFMovie();
        $swf->setDimension($this->getCanvasWidth(), $this->getCanvasHeight());
        $swf->setFrames(30);
        $swf->setRate(10);
        $swf->setBackground(0, 0, 0);

        foreach($this->elements AS $element)
        {
            if(is_a($element, 'GfxComponent'))
            {
                $element->renderSWF($swf);
            }
        }
        $swf->save($this->getOutputDestination());

    }

    private function renderGIF()
    {
        $this->setCanvas(imagecreatetruecolor($this->getCanvasWidth(), $this->getCanvasHeight()));

        foreach($this->elements as $element)
        {
            $updatedCanvas = $element->renderGif($this->getCanvas());
        }

        $this->setCanvas($updatedCanvas);

        imagegif($updatedCanvas, $this->getOutputDestination());

        chmod($this->getOutputDestination(), 0777);
    }

    public function createDestinationDir()
    {
        $parts = array($this->getCompany(), $this->getAdvertiser());

        $dir = OUTPUT_DIR;

        foreach($parts as $singleDir)
        {
            $dir .= $singleDir . '/';

            if(!is_dir($dir) && !file_exists($dir))
            {
                if(!mkdir($dir, 0777, true))
                {
                    die($dir.' mkdir failed');
                }
                chmod($dir, 0777);
            }
        }
        return $dir;
    }

    public function getOptionsForEditor()
    {
        return $this->editorOptions;
    }


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
     * @return mixed
     */
    public function getAdvertiser()
    {
        return $this->advertiser;
    }

    /**
     * @param mixed $advertiser
     */
    public function setAdvertiser($advertiser)
    {
        $this->advertiser = $advertiser;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }





    // Magic Methods
    public function __toString()
    {
        $string = '';
        foreach($this->elements AS $element) {
            $string .= print_r($element, true);
        }
        return $string;
    }
}
