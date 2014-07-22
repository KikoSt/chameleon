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
    private $sId;
    protected $elements;
    private $target;
    private $sSource;
    private $canvasWidth;
    private $canvasHeight;

    private $allowedTargets;

    public function __construct()
    {
        $this->elements = array();
        $this->allowedTargets = array('SWF', 'GIF');
    }

    public function setSource($sSource)
    {
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
        $this->sId =$sId;
    }

    public function getId()
    {
        return $this->sId;
    }

    public function addElement($element)
    {
        if(is_a($element, 'GfxComponent'))
        {
            $this->elements[] = $element;
        }
        else
        {
            throw new InvalidArgumentException();
        }
    }




    public function render()
    {
        if($this->target === 'SWF') {
            $this->renderSWF();
        } else if($this->target === 'GIF') {
            $this->renderGIF();
        }
    }

    private function renderSWF()
    {
        $fonts = array();
        $swf = new SWFMovie();
        $swf->setDimension($this->getCanvasWidth(), $this->getCanvasHeight());
        $swf->setFrames(30);
        $swf->setRate(10);
        $swf->setBackground(0, 0, 0);

        $fonts['normal'] = new SWFFont('fdb/bvs.fdb');

        $count = 0;
        $texts = array();

        foreach($this->elements AS $element) {
            if(is_a($element, 'GfxComponent')) {
                $element->renderSWF($swf);
            }
        }
        $swf->save('output.swf');

    }

    private function renderGIF()
    {
        $this->setCanvas(imagecreatetruecolor($this->getCanvasWidth(), $this->getCanvasHeight()));

        foreach($this->elements as $element)
        {
            if(is_a($element, 'GfxRectangle'))
            {
                $updatedCanvas = $element->renderGif($this->getCanvas());
            }
        }

        $this->setCanvas($updatedCanvas);

        imagegif($updatedCanvas, 'output.gif');
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
