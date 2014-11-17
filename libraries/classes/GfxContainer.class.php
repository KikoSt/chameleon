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
    private $frames; // number of overall frames for animation
    private $framerate;

    private $animatePreviews;

    // private $fontFamily;
    private $globalPrimaryColor;
    private $globalSecondaryColor;

    private $groups;

    // Path information:
    // the baseDir is either SVG_DIR or OUTPUT_DIR for now, depending on whether
    //   SVG operations or rendering is required -> NOT stored here
    // the adapted path is <companyId>/<advertiserId>/<categoryId, i.e.
    //   170/122/7
    // for general purposes, categoryId "0" can be used
    private $adaptedPath;

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

    private $previewMode;

    public function __construct()
    {
        $this->allowedTargets = array('SWF', 'GIF');
        $this->registry = array();
        $this->dataRegistry = array();
        $this->animationRegistry = array();
        $this->previewMode = false;
        $this->animatePreviews = true;
        $this->groups = array();

        // TODO: for now ...
        $this->framerate = 30;
        $this->frames = 0;
    }

    public function __destruct()
    {
        // exec('lsof -c php', $log);
        // var_dump($log);
        $this->cleanup();
    }

    private function cleanup()
    {
        if(count($this->registry) > 0)
        {
            foreach($this->registry AS $element)
            {
                fclose($element);
                unset($element);
            }
        }
        unset($this->registry);
        $this->registry = array();
    }

    public function registerDataUpdate($key, $element)
    {
        if(array_key_exists($key, $this->dataRegistry))
        {
            $this->dataRegistry[$key] = array();
        }
        $this->dataRegistry[$key][] = $element;
    }

    private function getSvg()
    {
        $svg = '';
        foreach($this->getElements() as $element)
        {
            $svg .= $element->getSvg();
        }
        return $svg;
    }

    /**
     * createSvg
     *
     * @access public
     */
    public function createSvg()
    {
        //create header
        $string = "<?xml version='1.0' encoding='UTF-8'?>";
        $string .= "\n" . '<svg width="'. $this->getCanvasWidth() .'" height="'. $this->getCanvasHeight() . '"';

        $string .= ' xmlns="http://www.w3.org/2000/svg" ';
        $string .= ' xmlns:cmeo="http://www.mediadecision.com/chameleon_namespace" ';
        $string .= ' xmlns:svg="http://www.w3.org/2000/svg" ';
        $string .= ' xmlns:xlink="http://www.w3.org/1999/xlink"';
        $string .= ' cmeo:font-family="' . $this->getFontFamily() . '"';
        $string .= ' cmeo:primary-color="' . $this->getPrimaryColor()->getHex() . '"';
        $string .= ' cmeo:secondary-color="' . $this->getSecondaryColor()->getHex() . '"';
        $string .= '>';
        $string .= "\n" . '<g>' . $this->getSvg() . '</g></svg>';
        return $string;
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
        if(file_exists(SVG_DIR . '/' . $this->adaptedPath . '/' . $source))
        {
            $this->source = simplexml_load_file(SVG_DIR . '/' . $this->adaptedPath . '/' . $source);
        }
        else if(is_string($source))
        {
            $this->source = simplexml_load_string($source);
        }
        else if($source instanceof SimpleXMLElement)
        {
            $this->source = $source;
        }
        else
        {
            throw new FileNotFoundException('File ' . $source . ' not found!');
        }
    }

    /**
     * parse
     *
     * Parse the xml tree, store settings and create all required elements. The create function of each element is
     * called with the relevant svg sub tree as an argument, and the element will process the data as required
     *
     * @access public
     * @return void
     */
    public function parse()
    {
        // delete all elements to avoid duplicates when "parse" is called accidentially more than once
        unset($this->elements);
        libxml_use_internal_errors(true);

        if(!($this->source instanceof SimpleXMLElement))
        {
            throw new Exception('No valid simplexml source element provided: ' . $this->source);
        }
        $svg = $this->source;

        $this->setCanvasWidth((float) $svg->attributes()->width);
        $this->setCanvasHeight((float) $svg->attributes()->height);
        $this->setPrimaryColor(new GfxColor($svg->attributes('cmeo', true)->{"primary-color"}));
        $this->setSecondaryColor(new GfxColor($svg->attributes('cmeo', true)->{"secondary-color"}));
        $this->setFontFamily((string) $svg->attributes('cmeo', true)->{"font-family"});

        $children = $svg->children();

        $main = $children->g;

        foreach($main->children() AS $child)
        {
            $gfxInstance = $this->getGfxInstance($child->getName());
            if($gfxInstance)
            {
                $gfxInstance->create($child);

                if($gfxInstance->getAnimations() > 0)
                {
                    $frameDuration = $gfxInstance->getFrameDuration();
                    if($this->frames < $frameDuration)
                    {
                        $this->frames = $frameDuration;
                    }
                }

                $this->addElement($gfxInstance);

                if(!empty($gfxInstance->getEditGroup()))
                {
                    if(!array_key_exists($gfxInstance->getEditGroup(), $this->groups))
                    {
                        $dummy = new GfxGroup($gfxInstance->getEditGroup(), $this);
                        $this->groups[$gfxInstance->getEditGroup()] = $dummy;
                    }
                }
            }
            unset($gfxInstance);
        }

        ksort($this->groups);

        foreach($this->groups AS $group)
        {
            $group->create();
        }
    }

    public function getElements()
    {
        return $this->elements;
    }

    public function addElement($element)
    {
        if($element instanceof GfxComponent)
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
    public function getOutputFilename()
    {
        if($this->getOutputName() != '')
        {
            $filename = $this->getOutputName();
        }
        else
        {
            if('' == $this->getId())
            {
                throw new Exception('Template id not set');
            }

            $filename  = $this->getId();

            if($this->getProductData())
            {
                $filename .= '_' . preg_replace("/[^a-zA-Z0-9]/", "", $this->getProductData()->getName());
                $filename .= '_' . $this->getProductData()->getProductId();
            }
            $filename .= '_' . $this->getCanvasHeight();
            $filename .= 'x' . $this->getCanvasWidth();
        }

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
        if('' == $this->getCompanyId() || '' == $this->getAdvertiserId() || '' === $this->getCategoryId())
        {
            throw new Exception('Company, Advertiser or Category ID missing');
        }
        // if there is a trailing / in OUTPUT_DIR, remove it, then assemble all parts to output directory path
        $parts = array((int) $this->getCompanyId(), (int) $this->getAdvertiserId(), (int) $this->getCategoryId());

        $outputDir = implode('/', $parts);

        return $outputDir;
    }

    public function saveSvg()
    {
        $outputDir = $this->calculateOutputDir();
        $this->setOutputDir($outputDir);

        $filename = $this->getOutputFilename() . '.svg';

        // if output dir doesn't exist, create it
        if(!is_dir(SVG_DIR . '/' . $outputDir))
        {
            // set the current umask to 0777
            $old = umask(0);
            if(!mkdir(SVG_DIR . '/' . $outputDir, 0777, true))
            {
                throw new Exception('Could not create directory ' . SVG_DIR . '/' . $outputDir);
            }
            // reset umask
            umask($old);
        }
        if(is_dir(SVG_DIR . '/' . $outputDir))
        {
            $handle = fopen(SVG_DIR . '/' . $outputDir . '/' . $filename, 'w');
            if(!$handle)
            {
                throw new Exception('Could not open file ' . SVG_DIR . '/' . $outputDir . '/' . $filename);
            }
            fwrite($handle, $this->createSvg());
            fclose($handle);
        }
        else
        {
            throw new Exception(SVG_DIR . '/' . $outputDir . ' not found!');
        }
    }

    private function createOutputDir()
    {
        if('' === $this->getOutputDir())
        {
            throw new Exception('Output dir not set');
        }
        else
        {
            $this->setOutputDir($this->calculateOutputDir());
        }

        $outputFormats = array(SVG_DIR, OUTPUT_DIR);

        foreach($outputFormats AS $basePath)
        {

            $dir = $basePath . '/' . $this->getOutputDir();

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
        }

        return $dir;
    }

    private function setOutputDir($outputDir)
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
            if($element instanceof GfxComponent)
            {
                $element->updateData();
            }
        }
    }


    public function register($element)
    {
        $this->registry[] = $element;
    }

    public function render()
    {
        $this->createOutputDir();
        $this->updateData();

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
        $swf->setFrames($this->frames);
        $swf->setRate($this->framerate);
        $swf->setBackground(0, 0, 0);

        foreach($this->elements AS $element)
        {
            if($element instanceof GfxComponent)
            {
                $element->renderSWF($swf);
            }
        }
        $swf->save(OUTPUT_DIR . '/' . $this->getOutputDir() . '/' . $this->getOutputFilename() . '.swf');

        $swf = null;
        unset($swf);
        gc_collect_cycles();
    }

    private function renderGIF()
    {
        //set the color for the layer
        $color = new ImagickPixel("rgba(127,127,127, 0)");

        //create the stage (container for the single frames)
        $stage = new Imagick();
        // $stage->newimage($this->getCanvasWidth(), $this->getCanvasHeight(), $color);
        $stage->setFormat('gif');

        if($this->animatePreviews) {
            $frameCount = $this->getFrames();
        } else {
            $frameCount = 1;
        }
        $skipFrames = (int) 0;
        $imageDelay = 6; // 3.9;      // basic (initial) delay value
        $delay = $imageDelay;   // actual delay
        $animationElements = array();

        foreach($this->elements AS $element)
        {
            if(count($element->getAnimations()) > 0)
            {
                $animationElements[] = $element;
            }
        }

        /**
         *  Render the first frame
         * non-animated elements will only be rendered once
        **/
        $layerStack = array();
        $background = new Imagick();
        $background->newImage($this->getCanvasWidth(), $this->getCanvasHeight(), $color);
        foreach ($this->elements as $element)
        {
            $animations = $element->getAnimations();

            if(count($animations) == 0)
            {
                $layer = $element->renderGif(array());
                $background->compositeImage($layer, Imagick::COMPOSITE_DEFAULT, 0, 0);
            }
        }
        /**
         * Done rendering the first frame!
        **/

        //create frames
        for($i = 0; $i < $frameCount; $i += ($skipFrames+1))
        {
            $layerStack = array();

            $skip = true;

            //loop through all elements
            foreach ($animationElements as $element)
            {
                $skip = true;
                if(in_array($i, $element->getAnimationKeyframes()))
                {
                   $skip = false;
                }
                //add the elements to an array
                $animationStep = $element->getAnimationStep($i);
                $layerStack[] = $element->renderGif($animationStep, $skip);
            }

            if($skip)
            {
                // increase delay
                $delay += $imageDelay;
                continue;
            }

            //create container for the single frame
            $frame = new Imagick();
            $frame->newImage($this->getCanvasWidth(), $this->getCanvasHeight(), $color);
            $i > 0 ? $imageDispose = 3 : 0;
            $i > 0 ? $delay = $delay : 0;
            $frame->setImageDispose($imageDispose);
            $frame->setImageDelay($delay);
            //composite the single images
//            if($i == 0)
//            {
                $frame->compositeImage($background, Imagick::COMPOSITE_DEFAULT, 0, 0);
//            }
//            else
//            {
                if(count($layerStack) > 0)
                {
                    foreach($layerStack as $singleImage)
                    {
                        if($singleImage instanceof Imagick)
                        {
                            $frame->compositeImage($singleImage, Imagick::COMPOSITE_DEFAULT, 0, 0);
                        }
                    }
                }
//            }

            //add the complete frame to the stage
            $stage->addImage($frame);
            // reset delay
            $delay = $imageDelay;
        }

        //complete the banner
        $miffpath = OUTPUT_DIR . '/' . $this->getOutputDir() . '/' . $this->getOutputFilename() . '.miff';
        $gifpath = OUTPUT_DIR . '/' . $this->getOutputDir() . '/' . $this->getOutputFilename() . '.gif';
        $animatedGif = $stage->writeImages($gifpath, true);

        // exec('convert ' . $path . ' -coalesce -layers optimize-frame optframe_erase.gif gif_anim_montage optframe_erase.gif optframe_erase_frames.gif');
        // exec('gif_anim_montage ' . $path . ' -coalesce -layers optimize-frame optframe_erase.gif gif_anim_montage optframe_erase.gif optframe_erase_frames.gif');
        // exec('convert ' . $path . ' ( -clone 0--1 -background none +append -quantize trnsparent -colors 63 -unique-colors - write mpr:cmap +delete ) - map mpr:cmap ' . $path);
//         exec('convert ' . $miffpath . ' +matte +map ' . $gifpath)
         exec('convert ' . $gifpath . ' -fuzz 8% -layers optimize-transparency +map ' . $gifpath);


        unset($animatedGif);
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


    public function changeElementValue($formData)
    {
        $valueList = array();

        // process all form data and store the information referenced by their element id in a dictionary
        foreach($formData AS $key => $value)
        {
            // svg values consist of element id and parameter name:
            // background#width
            // values without # are ignored (for example the action parameter)
            if(strpos($key, '#'))
            {
                list($id, $parameter) = explode('#', $key);

                if(!array_key_exists($id, $valueList))
                {
                    $valueList[$id] = array();
                }
                $valueList[$id][$parameter] = $value;
            }
        }

        // global parameters are identified by the template id instead of the element id
        $globalSettings = $valueList[$this->getId()];

        $dimensions = $this->getGlobalWidthAndHeight($globalSettings['globalDimensions']);

        $fontFamily     = $globalSettings['fontFamily'];
        $primaryColor   = new GfxColor($globalSettings['primary-color']);
        $secondaryColor = new GfxColor($globalSettings['secondary-color']);

        $this->setCanvasWidth($dimensions->width);
        $this->setCanvasHeight($dimensions->height);

        $this->setPrimaryColor($primaryColor);
        $this->setSecondaryColor($secondaryColor);

        $this->setFontFamily($fontFamily);

        // now we've got a dictionary like that:
        // valueList['price_ribbon_1']['x'] = 50;
        foreach($this->getElements() as $element)
        {
            // now get the id of one element after another and read the values from
            // the prepared dictionary
            $id = $element->getId();
            $element->disableShadow();
            $element->disableStroke();
            foreach($valueList[$id] AS $param => $value)
            {
                $func="set" . ucwords($param);

                if(substr($param, 0, 6) === 'shadow')
                {
                    // what ever comes first, could be shadow, shadowColor or shadowDist
                    if(empty($element->getShadow()))
                    {
                        $shadowColor = new GfxColor('#000000');
                        $shadowDist = 2;
                        $shadow = new GfxShadow($shadowColor, $shadowDist);
                        $element->setShadow($shadow);
                    }
                }

                if(substr($param, 0, 6) === 'stroke')
                {
                    // what ever comes first, could be shadow, shadowColor or shadowDist
                    if(empty($element->getStroke()))
                    {
                        $strokeColor = new GfxColor('#000000');
                        $strokeWidth = 1;
                        $stroke = new GfxStroke($strokeColor, $strokeWidth);
                        $element->setStroke($stroke);
                    }
                }

                if($param === "fill")
                {
                    $color = new GfxColor($value);
                    $element->$func($color);
                }
                elseif($param === 'shadow')
                {
                    $element->enableShadow();
                }
                elseif($param === 'stroke')
                {
                    $element->enableStroke();
                }
                elseif($param === "shadowColor")
                {
                    // $element->enableShadow();
                    $element->getShadow()->setColor(new GfxColor($value));
                }
                elseif($param === "shadowDist")
                {
                    // empty. currently prevent errors TO BE FIXED
                    // $element->enableShadow();
                    $element->getShadow()->setDist($value);
                }
                elseif($param === "strokeWidth")
                {
                    $element->getStroke()->setWidth($value);
                }
                elseif($param === "strokeColor")
                {
                    $element->getStroke()->setColor(new GfxColor($value));
                }
                elseif($param === 'animation')
                {
                    $element->setAnimation($value);
                }
                else
                {
                    $element->$func($value);
                }
            }
        }
    }

    /* **************************************
              Accessors
    ***************************************** */
    public function setId($sId)
    {
        $this->id =$sId;
    }

    public function getId()
    {
        return $this->id;
    }

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

    private function calculateAdaptedPath()
    {
        if($this->companyId !== null && $this->advertiserId !== null)
        {
            $this->adaptedPath = $this->getCompanyId() . '/' . $this->getAdvertiserId() . '/' . (int)$this->getCategoryId();
        }

    }

    /**
     * @param mixed $advertiser
     */
    public function setAdvertiserId($advertiserId)
    {
        $this->advertiserId = $advertiserId;
        $this->calculateAdaptedPath();
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
        $this->calculateAdaptedPath();
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

    public function getGroups()
    {
        return $this->groups;
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
        $this->calculateAdaptedPath();
    }

    /**
     * getCanvas
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

    /**
     * Get previewMode.
     *
     * @return previewMode.
     */
    public function getPreviewMode()
    {
        return $this->previewMode;
    }

    /**
     * Set previewMode.
     *
     * @param previewMode the value to set.
     */
    public function setPreviewMode($previewMode)
    {
        $this->previewMode = $previewMode;
    }

    /**
     * Get globalFontFamily.
     *
     * @return globalFontFamily.
     */
    public function getFontFamily()
    {
        return $this->globalFontFamily;
    }

    /**
     * Set globalFontFamily.
     *
     * @param globalFontFamily the value to set.
     */
    public function setFontFamily($globalFontFamily)
    {
        $this->globalFontFamily = $globalFontFamily;
    }

    /**
     * Get globalPrimaryColor.
     *
     * @return globalPrimaryColor.
     */
    public function getPrimaryColor()
    {
        return $this->globalPrimaryColor;
    }

    /**
     * Set globalPrimaryColor.
     *
     * @param globalPrimaryColor the value to set.
     */
    public function setPrimaryColor(GfxColor $globalPrimaryColor)
    {
        $this->globalPrimaryColor = $globalPrimaryColor;
    }

    /**
     * Get globalSecondaryColor.
     *
     * @return globalSecondaryColor.
     */
    public function getSecondaryColor()
    {
        return $this->globalSecondaryColor;
    }

    /**
     * Set globalSecondaryColor.
     *
     * @param globalSecondaryColor the value to set.
     */
    public function setSecondaryColor(GfxColor $globalSecondaryColor)
    {
        $this->globalSecondaryColor = $globalSecondaryColor;
    }

    private function getGlobalWidthAndHeight($globalDimensions)
    {
        $dimensions = new stdClass();

        $explode = explode('x', $globalDimensions);
        $dimensions->width = $explode[0];
        $dimensions->height = $explode[1];

        return $dimensions;
    }

    public function getFramerate()
    {
        return $this->framerate;
    }

    public function setFramerate($framerate)
    {
        if(is_numeric($framerate))
        {
            $this->framerate = $framerate;
        }
    }

    /**
     * @return int
     */
    public function getFrames()
    {
        return $this->frames;
    }

    /**
     * @param int $frames
     */
    public function setFrames($frames)
    {
        $this->frames = $frames;
    }


    public function animatePreviews($animate)
    {
        if($animate === true)
        {
            $this->animatePreviews = true;
        }
        else
        {
            $this->animatePreviews = false;
        }
    }
}
