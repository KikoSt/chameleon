<?php
/**
 * Comment here
 *
 * User: thomas.hummel@mediadecision.com
 * Date: 17/07/2014
 * Time: 07:30
 */

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

        $this->createGfxComponents($svg);
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
            if(is_a($element, 'GfxRectangle') || is_a($element, 'GfxText')) {
                $element->renderSWF($swf);
            }
        }
        $swf->save('output.swf');

    }

    private function renderGIF()
    {

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

    private function createGfxComponents($oSvg)
    {
        if(empty($oSvg))
        {
            throw new InvalidArgumentException();
        }

        $aComponentSecondGen = (array)$oSvg->children()->children();

        foreach($aComponentSecondGen as $key => $aSingleComponent)
        {
            $oClass = new GfXComponent();
            switch($key)
            {
                case "rect":
                {
                    $oClass = new GfxRectangle();
                    break;
                }
                case "text":
                {
                    $oClass = new GfxText();
                    break;
                }
                case "image":
                {
                    $oClass = new GfxImage();
                    break;
                }
                case "ellipse":
                {
                    //TODO nothing for now
                    break;
                }
            }
            $this->createSingleComponent($oClass, $aSingleComponent, $oSvg);
        }
        Debug::browser($this->elements);
    }

    private function createSingleComponent($class, $aComponent, $oSvg)
    {
        foreach($aComponent as $key => $oSingleComponent)
        {
            if(is_a($class, 'GfxText'))
            {
                $class->setText($oSingleComponent);
            }

            $class->setLink($oSvg->g->image[$key]->attributes('xlink', true)->href);

            $aAttributes = $oSvg->g->text[$key]->attributes();

            foreach ($aAttributes as $attributeKey => $value)
            {
                if (!empty($value))
                {
                    $this->useDynamicSetter($class, $attributeKey, $value);
                }
            }
            $this->addElement($class);
        }
    }

    public function useDynamicSetter(GfXComponent $oComponent, $sFuncName, $param)
    {
        if(strpos($sFuncName, "-") !== false)
        {
            $aFuncName = explode("-", $sFuncName);

            $sFuncName = '';

            foreach($aFuncName as $part)
            {
                $sFuncName .= ucwords($part);
            }
        }

        if($sFuncName === "stroke" || $sFuncName === "fill")
        {
            $oColor = new GfxColor();
            $oColor->setHex($param);
            $param = $oColor;
        }

        $func = "set".ucwords($sFuncName);

        if(method_exists($oComponent, $func))
        {
            $oComponent->$func($param);
        }
        else
        {
            //throw new BadMethodCallException();
        }
    }
}