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
    private $source;
    private $canvasWidth;
    private $canvasHeight;
    private $canvas;
    private $outputName; // default name if not set!
    private $outputDir;
    private $companyId;
    private $advertiserId;
    private $categoryId;
    private $editorOptions;
    private $allowedTargets;

    private $productData;

    private $registry;

    // the data registry consists of several "list", one for each relevant data component:
    // - price
    // - oldPrice
    // - productUrl
    // etc.
    // The respective "sub-registry" will store type and ID of each component
    // Every registered component will be updated once for each product
    private $dataRegistry;
    private $animationRegistry;

    public function __construct()
    {
        $this->allowedTargets = array('SWF', 'GIF');
        $this->dataRegistry = array();
        $this->animationRegistry = array();
    }

    public function __destruct()
    {
        // exec('lsof -c php', $log);
        // var_dump($log);
        $now = time();
        foreach($this->registry AS $element)
        {
            fclose($element);
            unset($element);
        }
        $then = time();
        echo 'D: ' . ($then - $now);
        unset($this->registry);
    }

    public function cleanup()
    {
        $now = time();
        foreach($this->registry AS $element)
        {
            fclose($element);
            unset($element);
        }
        unset($this->registry);
        $this->registry = array();
        $then = time();
        echo 'D: ' . ($then - $now);
    }

    public function registerDataUpdate($key, $element)
    {
        if(array_key_exists($key, $this->dataRegistry))
        {
            $this->dataRegistry[$key] = array();
        }
        $this->dataRegistry[$key][] = $element;
    }

    /**
     * @return mixed
     */
    public function getCanvas()
    {
        return $this->canvas;
    }

    public function getSvg()
    {
        $svg = '';
        foreach($this->getElements() as $element)
        {
            $svg .= $element->getSvg();
        }
        return $svg;
    }

    public function createSvg()
    {
        //create header
        $string = "<?xml version='1.0' encoding='UTF-8'?>";
        $string .= "\n" . '<svg width="'. $this->getCanvasWidth() .'" height="'. $this->getCanvasHeight() . '"';

        $string .= ' xmlns="http://www.w3.org/2000/svg" ';
        $string .= ' xmlns:cmeo="http://www.mediadecision.com/chameleon_namespace" ';
        $string .= ' xmlns:svg="http://www.w3.org/2000/svg" ';
        $string .= ' xmlns:xlink="http://www.w3.org/1999/xlink"';
        $string .= '>';
        $string .= "\n" . '<g>' . $this->getSvg() . '</g></svg>';
        return $string;
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

    public function setSource($source)
    {

        if(file_exists($source))
        {
            $source = SVG_DIR . $source;
            $this->source = $source;
        }
        else if(is_string($source))
        {
            $this->source = $source;
        }
        else if(is_a($source, 'SimpleXMLElement'))
        {
            $this->source = $source;
        }
        else
        {
            throw new FileNotFoundException('File ' . $source . ' not found!');
        }
    }

    public function parse()
    {
        libxml_use_internal_errors(true);

        if(is_a($this->source, 'SimpleXMLElement'))
        {
            $svg = $this->source;
        }
        else if(file_exists(SVG_DIR . $this->source))
        {
            $svg = new SimpleXMLElement(file_get_contents(SVG_DIR . $this->source));
        }
        else if (is_string($this->source) && simplexml_load_string($this->source))
        {
            $svg = simplexml_load_string($this->source);
        }
        else
        {
            throw new Exception('No valid source provided (file path, simplexml svg object, svg string');
        }

        $main = $svg->children();

        $this->setCanvasWidth((float) $svg->attributes()->width);
        $this->setCanvasHeight((float) $svg->attributes()->height);

        foreach($main->children() AS $child)
        {
            $gfxInstance = $this->getGfxInstance($child->getName());
            if($gfxInstance)
            {
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
        if($this->getOutputName() != '')
        {
            $filename = $this->getOutputName();
        }
        else
        {
            if('' === $this->getCompanyId() || '' === $this->getAdvertiserId())
            {
                throw new Exception('Company or advertiser id not set');
            }
            if('' == $this->getId())
            {
                throw new Exception('Template id not set');
            }

            // $filename = $this->getCompanyId();
            // $filename .= '_' . $this->getAdvertiserId();
            $filename  = $this->getId();
            $filename .= '_' . preg_replace("/[^a-zA-Z0-9]/", "", $this->getProductData()->getName());
            $filename .= '_' . $this->getProductData()->getProductId();
            $filename .= '_' . $this->getCanvasHeight();
            $filename .= 'x' . $this->getCanvasWidth();
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
        if('' == $this->getCompanyId() || '' == $this->getAdvertiserId() || '' == $this->getCategoryId())
        {
            throw new Exception('Company, Advertiser or Category ID missing');
        }
        // if there is a trailing / in OUTPUT_DIR, remove it, then assemble all parts to output directory path
        $parts = array(rtrim(OUTPUT_DIR, '/'), $this->getCompanyId(), $this->getAdvertiserId(), $this->getCategoryId());

        $outputDir = implode('/', $parts);

        return $outputDir;
    }

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

    public function setOutputDir($outputDir)
    {
        $this->outputDir = $outputDir;
    }


    public function getOutputDir()
    {
        if(!isset($this->outputDir) || '' == $this->outputDir)
        {
            $this->setOutputDir($this->calculateOutputDir());
        }
        return $this->outputDir;
    }

    public function updateData()
    {
        foreach($this->elements AS $element)
        {
            if(is_a($element, 'GfxComponent'))
            {
                $element->updateData();
            }
        }
    }

    public function render()
    {
        $this->createOutputDir();
        $this->updateData();

        echo 'rendering ' . $this->target . "\n";
        $now = time();
        if($this->target === 'SWF')
        {
            $this->renderSWF();
        }
        else if($this->target === 'GIF')
        {
            $this->renderGIF();
        }
        $then = time();
        $duration = $then - $now;
        // echo 'now was ' . $now . "\n";
        // echo 'then is ' . $then . "\n";
        // echo 'Dur: ' . $duration . "\n";
    }



    public function register($element)
    {
        $this->registry[] = $element;
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
        $swf->save($this->getOutputDir() . '/' . $this->getOutputFilename());

        $swf = null;
        unset($swf);
        gc_collect_cycles();

    }

    private function renderGIF()
    {
        $this->setCanvas(imagecreatetruecolor($this->getCanvasWidth(), $this->getCanvasHeight()));

        foreach($this->elements as $element)
        {
            $updatedCanvas = $element->renderGif($this->getCanvas());
        }

        $this->setCanvas($updatedCanvas);

        imagegif($updatedCanvas, $this->getOutputDir() . '/' . $this->getOutputFilename());

        imageDestroy($updatedCanvas);

        chmod($this->getOutputDir() . '/' . $this->getOutputFilename(), 0777);
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
            $gfxInstance = new $componentTypes[$type]($this);
        }
        else
        {
            return false;
        }
        return $gfxInstance;
    }

    /**
     * registerSubstitution
     *
     * @param mixed $dataId
     * @param mixed $gfxElement
     * @access public
     * @return void
     */
    public function registerSubstitution($dataId, $gfxElement)
    {
        // check if there is already a "registry" for the given dataId
        // if not, create it
        // register the given element for dataId updates/substitutions
    }

    public function changeElementValue($formData)
    {
        $now = time();
        echo "Now = " . $now . "\n";
        //iterate all svg elements
        foreach($this->getElements() as $element)
        {
            $id = $element->getId();

            //iterate form data
            foreach($formData as $key => $value)
            {
                $cleansedKey = explode('#', $key);

                if(isset($cleansedKey[1]))
                {
                    $param = $cleansedKey[1];
                }

                // form data containing the current element found?
                if(strcasecmp($cleansedKey[0], $id) == 0)
                {
                    $func="set" . ucwords($param);

                    if($param === "fill" || $param === "shadowColor")
                    {
                        $color = new GfxColor($value);
                        $element->$func($color);
                    }
                    elseif($param === "stroke")
                    {
                        $stroke = new GfxStroke(new GfxColor($value), 1);
                        $element->$func($stroke);
                    }
                    else
                    {
                        $element->$func($value);
                    }
                }
            }
        }
        $then = time();
        echo "Then = " . $then . "\n";
        echo 'Duration = ' . ($then - $now) . "\n\n";
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
     * @return int
     */
    public function getAdvertiserId()
    {
        return $this->advertiserId;
    }

    /**
     * @param mixed $advertiser
     */
    public function setAdvertiserId($advertiserId)
    {
        $this->advertiserId = $advertiserId;
    }

    /**
     * @return mixed
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @param mixed $company
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
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

    /**
     * Get productData.
     *
     * @return productData.
     */
    public function getProductData()
    {
        return $this->productData;
    }

    /**
     * Set productData.
     *
     * @param productData the value to set.
     */
    public function setProductData(ProductModel $productData)
    {
        $this->productData = $productData;
    }

    /**
     * Get categoryId.
     *
     * @return categoryId.
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set categoryId.
     *
     * @param categoryId the value to set.
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }
}
