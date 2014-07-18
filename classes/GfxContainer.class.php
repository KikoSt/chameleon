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

    private function createGfxComponents($oComponent)
    {
        if(empty($oComponent))
        {
            throw new InvalidArgumentException();
        }

        $aComponentSecondGen = (array)$oComponent->children()->children();

        foreach($aComponentSecondGen as $key => $aSingleComponent)
        {
            switch($key)
            {
                case "rect":
                {
                    $this->createGfxRectangle($aSingleComponent);
                    break;
                }
                case "text":
                {
                    $this->createGfxText($aSingleComponent, $oComponent);
                    break;
                }
                case "image":
                {

                    break;
                }
                case "ellipse":
                {

                    break;
                }
            }
        }
        Debug::browser($this->elements);
    }

    private function createGfxText($aComponent, $oCoreComponent)
    {
        $oGfxText = new GfxText();

        foreach($aComponent as $key => $text)
        {
            $oGfxText->setText($text);

            $aAttributes = $oCoreComponent->g->text[$key]->attributes();

            foreach ($aAttributes as $key => $value)
            {
                if (!empty($value))
                {
                    switch($key)
                    {
                        case "id":
                        {
                            $oGfxText->setId($value);
                            break;
                        }
                        case "stroke":
                        {
                            $oColor = new GfxColor();
                            $oColor->setHex($value);
                            $oGfxText->setStroke($oColor);
                            break;
                        }
                        case "x":
                        {
                            $oGfxText->setX($value);
                            break;
                        }
                        case "y":
                        {
                            $oGfxText->setY($value);
                            break;
                        }
                        case "fill":
                        {
                            $oColor = new GfxColor();
                            $oColor->setHex($value);
                            $oGfxText->setColor($oColor);
                            break;
                        }
                        case "width":
                        {
                            $oGfxText->setWidth($value);
                            break;
                        }
                        case "height":
                        {
                            $oGfxText->setHeight($value);
                            break;
                        }
                        case "font-family":
                        {
                            $oGfxText->setFontFamily($value);
                            break;
                        }
                        case "font-size":
                        {
                            $oGfxText->setFontSize($value);
                            break;
                        }
                    }
                }
            }
            $this->addElement($oGfxText);
        }
    }

    private function createGfxRectangle($aComponent)
    {
        $oGfxRectangle = new GfxRectangle();

        foreach($aComponent as $oSingleComponent)
        {
            $aSingleComponent = $oSingleComponent->attributes();

            foreach($aSingleComponent as $key => $value)
            {
                if(!empty($value))
                {
                    switch($key)
                    {
                        case "id":
                        {
                            $oGfxRectangle->setId($value);
                            break;
                        }
                        case "stroke":
                        {
                            $oColor = new GfxColor();
                            $oColor->setHex($value);
                            $oGfxRectangle->setStroke($oColor);
                            break;
                        }
                        case "x":
                        {
                            $oGfxRectangle->setX($value);
                            break;
                        }
                        case "y":
                        {
                            $oGfxRectangle->setY($value);
                            break;
                        }
                        case "fill":
                        {
                            $oColor = new GfxColor();
                            $oColor->setHex($value);
                            $oGfxRectangle->setColor($oColor);
                            break;
                        }
                        case "width":
                        {
                            $oGfxRectangle->setWidth($value);
                            break;
                        }
                        case "height":
                        {
                            $oGfxRectangle->setHeight($value);
                            break;
                        }
                    }
                }
            }
            $this->addElement($oGfxRectangle);
        }
    }

    /**
     * TODO just for development
     */
    private function getMethods($class)
    {
        $aMethods = get_class_methods($class);

        foreach($aMethods as $key => $aSingleMethod)
        {
            if(!preg_match("/^set[a-zA-Z]*/", $aSingleMethod))
            {
                unset($aMethods[$key]);
            }
        }
        Debug::browser($aMethods);
    }
}
