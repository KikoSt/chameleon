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

    public function __construct()
    {
        $this->elements = array();
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
        if(is_a($element, 'GfxComponent')) {
            $this->elements[] = $element;
            echo 'Success!';
        } else {
            echo 'No!';
        }
    }

    public function render()
    {

    }

    public function setTarget($target)
    {
        if(!in_array($target, $this->allowedTargets)) {
            die('What a terrible death!');
        } else {
            $this->target = $target;
        }
    }

    public function getTarget()
    {
        return $this->target;
    }

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
//                    $this->createGfxText($oSingleComponent, $oComponent);
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
    }

    private function createGfxText($oSingleComponent, $oComponent)
    {
//        foreach($aText as $key => $sSingleText)
//        {
//            $aAttributes = $oComponent->g->text[$key];
//
//            Debug::browser($aAttributes, true);
//
//            $oGfxText = new GfxText();
//
//            if(isset($sSingleText))
//            {
//                $oGfxText->setText($sSingleText);
//            }
//            else
//            {
//                throw new InvalidArgumentException();
//            }
//
//            if(isset($aAttributes->id))
//            {
//                $oGfxText->setId($aAttributes->id);
//            }
//        }


        //$oText = new GfxText($color);
//$oText->setId("headline");
//$oText->setText("das ist ein toller text");
//$oText->setFont(new SWFFont('fdb/bvs.fdb'));
//$oText->setColor($color);
//$oText->setHeight(10);
//$oText->setPosition($oText->getWidth(), 0);
//$oText->create();
    }

    private function createGfxRectangle($aComponent)
    {
        $oGfxRectangle = new GfxRectangle();

        foreach($aComponent as $oSingleComponent)
        {
            $aSingleComponent = $oSingleComponent->attributes();

            foreach($aSingleComponent as $key => $value)
            {
                Debug::browser($key." - ".$value);

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
                            $oGfxRectangle->setStroke($value);
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

                            break;
                        }
                        case "width":
                        {

                            break;
                        }
                        case "height":
                        {

                            break;
                        }
                    }
                }

            }
        }

//        private $x, $y;
//    private $width, $height;
//    private $id;
//    private $color;
//    private $stroke;
    }
}
