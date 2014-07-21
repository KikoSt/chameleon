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

    }


    private function getGfxInstance($type)
    {
        // easily extendable, just add new types here
        $componentTypes = array(    'rect' => 'GfxRectangle',
                                    'text' => 'GfxText',
                                    'image' => 'GfxImage',
                                    'ellipse' => 'GfxEllipse');
        if(array_key_exists($type, $componentTypes)) {
            // create instance of requested class based on the above mapping
            $gfxInstance = new $componentTypes[$type]();
        } else {
            return false;
        }
        return $gfxInstance;
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
            continue;
            $gfxInstance = $this->getGfxInstance($key);
            if($gfxInstance) {
                $this->createSingleComponent($gfxInstance, $aSingleComponent, $oSvg);
            }
        }
        Debug::console($this->elements);
    }



    private function createSingleComponent($gfxInstance, $aComponent, $oSvg)
    {
        foreach($aComponent as $key => $oSingleComponent)
        {
            if(is_a($gfxInstance, 'GfxText'))
            {
                $gfxInstance->setText($oSingleComponent);
            }

            // $gfxInstance->setLink($oSvg->g->image[$key]->attributes('xlink', true)->href);

            $aAttributes = $oSvg->g->text[$key]->attributes();

            foreach ($aAttributes as $attributeKey => $value)
            {
                if (!empty($value))
                {
                    $this->useDynamicSetter($gfxInstance, $attributeKey, $value);
                }
            }
            $this->addElement($gfxInstance);
        }
    }

    public function useDynamicSetter($oComponent, $sFuncName, $param)
    {
        // using simplexmlelements here, this "reduces" the simplexmlelement object
        // to the mere value
        $param = (string)$param;

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

        echo get_class($oComponent) . ': ' . $func . '; ' . print_r($param, 1) . "\n\n";

        if(method_exists($oComponent, $func))
        {
            $oComponent->$func($param);
        }
        else
        {
            //throw new BadMethodCallException();
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
