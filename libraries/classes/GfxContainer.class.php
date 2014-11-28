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
    private $numFrames; // number of overall frames for animation
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
    private $globalAnimationKeyframes;

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

        $this->maxDuration = 10;

        // TODO: for now ...
        $this->framerate = 30;
        $this->numFrames = 0;
        $this->globalAnimationKeyframes = array();
    }

    public function __destruct()
    {
        // exec('lsof -c php', $log);
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



    /**
     * getSvg
     *
     * @access private
     * @return void
     */
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



    public function calculateFrameDuration()
    {
        $maxDuration = 0;
        foreach($this->elements AS $gfxInstance)
        {
            $frameDuration = $gfxInstance->getFrameDuration();
            if($maxDuration < $frameDuration)
            {
                $maxDuration = $frameDuration;
            }
        }
        return $maxDuration;
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
        libxml_use_internal_errors(true);

        // delete all elements to avoid duplicates when "parse" is called accidentially more than once
        unset($this->elements);
        unset($this->globalAnimationKeyframes);
        $this->numFrames = 0;

        $this->globalAnimationKeyframes = array();

        if(!($this->source instanceof SimpleXMLElement))
        {
            throw new Exception('No valid simplexml source element provided: ' . $this->source);
        }
        $svg = $this->source;

        // parse global banner information
        $this->setCanvasWidth((float) $svg->attributes()->width);
        $this->setCanvasHeight((float) $svg->attributes()->height);
        $this->setPrimaryColor(new GfxColor($svg->attributes('cmeo', true)->{"primary-color"}));
        $this->setSecondaryColor(new GfxColor($svg->attributes('cmeo', true)->{"secondary-color"}));
        $this->setFontFamily((string) $svg->attributes('cmeo', true)->{"font-family"});

        $children = $svg->children();

        // "g" is part of the svg standard for the node containing the actual elements
        $main = $children->g;

        foreach($main->children() AS $child)
        {
            // the svg node name (NOT the element name which is stored in the ID!)
            $gfxInstance = $this->getGfxInstance($child->getName());

            if($gfxInstance)
            {
                // read all information from svg into child GfxElement
                $gfxInstance->create($child);

                // and store the GfxElement
                $this->addElement($gfxInstance);

                // calculate frame length by finding max length of all child elements
                $this->numFrames = $this->calculateFrameDuration();

                // generate entries for "global" animation keyframe list
                $animationKeyframes = $gfxInstance->getAnimationKeyframes();
                if(is_array($animationKeyframes))
                {
                    $this->globalAnimationKeyframes = $animationKeyframes + $this->globalAnimationKeyframes;
                }

                // create edit group if required
                if(!empty($gfxInstance->getEditGroup()))
                {
                    if(!array_key_exists($gfxInstance->getEditGroup(), $this->groups))
                    {
                        $group = new GfxGroup($gfxInstance->getEditGroup(), $this);
                        $this->groups[$gfxInstance->getEditGroup()] = $group;
                        unset($group);
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
        $swf->setFrames($this->numFrames);
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



    /**
     * renderGIF
     *
     * create the (animated or static) GIF and save it to file;
     *
     *
     * @access private
     * @return void
     */
    private function renderGIF()
    {
        //set the color for the layer
        $color = new ImagickPixel("rgba(127,127,127, 0)");
        $imageDispose = 0;
        $imageDelay   = 6; // basic (initial) delay value per frame; most browsers do not support
                           // less than 6 frames, leading to an even worse (slower, stuttering)
                           // result
        $delay        = $imageDelay;   // actual delay, will be increased when frames are skipped

        $animationElements = array();

        //create the stage (container for the single frames)
        $stage = new Imagick();
        $stage->setFormat('gif');

        foreach($this->elements AS $element)
        {
            if(count($element->getAnimations()) > 0)
            {
                $animationElements[] = $element;
            }
        }

        /**
         *   Render the first frame
         *
         * non-animated elements will only be rendered once
         * The background is kept in memory and will be added to each frame
         * Alternatively we could add the background to the animation as
         * a first frame with a delay of zero ms, but most browsers do still
         * add a small delay making the animation "blink".
         * The static background parts will be removed later (see exec'd
         * optimization below).
        **/
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

        /**
         *   Create frames
         *
         * Only frames which are stored in the globalAnimationKeyframes-list will
         * be rendered! This list is a simple array containing all keyframe numbers
         * from all elements with animations; Keyframes are calculated based on several
         * aspects:
         * - change in animation
         * - time based (every second, third ...) frame
         *
         * Originally, here was a "skipframe" variable in this method; This has been
         * removed since the keyframes are already calculated time based as well and
         * mixing this up would lead to "jerking" results
        **/

        // If the flag for animation isn't set, render only one frame
        $frameCount = $this->animatePreviews ? $this->getNumFrames() : 1;

        // 30 or 15 seconds of animation right now!
        // imageDelay is measured in 1/100th, so an imageDelay of 6 means ideally
        // 100 / 6 images are displayed per second AND
        // we can have
        // 15 * (100 / 6) = 250 OR
        // 30 * (100 / 6) = 500
        // images (frames) in our GIF
        // This again means that we can have
        // 250 (500) / frameCount
        // iterations
        if($frameCount > 1) {
            $overallFrames = $this->maxDuration * (100 / $imageDelay);
            $numIterations = ceil($overallFrames / $frameCount);
        }
        else
        {
            $numIterations = 1;
        }

        $imgTime = 0;
        $recTime = 0;
        $texTime = 0;

        for($i = 0; $i <= $frameCount; $i++)
        {
            $layerStack = array(); // store all "subframes", i.e. elements
                                   // has to be resetted on each iteration

            // TODO: if there are no elements that have to be animated, frameCount should always be 1
            $skip = true;

            //loop through all elements
            foreach ($animationElements as $element)
            {
                $start = microtime(true);
                $skip = true;
                if(in_array($i, $this->globalAnimationKeyframes))
                {
                   $skip = false;
                }
                //add the elements to an array
                $animationStep = $element->getAnimationStep($i);

                // even if we don't render this frame to the final gif, the
                // appropriate properties have to be changed according to the
                // animationStep information
                $layerStack[] = $element->renderGif($animationStep, $skip);
                $dur = microtime(true) - $start;
                switch(get_class($element))
                {
                    case 'GfxImage':
                    $imgTime += $dur;
                    break;

                    case 'GfxText':
                    $texTime += $dur;
                    break;

                    case 'GfxRectangle':
                    $recTime += $dur;
                    break;
                }
            }
            // never skip the first frame!
            if($i == 0) $skip = false;

            if($skip)
            {
                // increase delay to prevend stuttering/jerky animation if
                // frame will be omitted
                $delay += $imageDelay;
                continue;
            }

            // if not skipping, we create a container for this single frame
            $frame = new Imagick();
            $frame->newImage($this->getCanvasWidth(), $this->getCanvasHeight(), $color);

            $i > 0 ? $imageDispose = 3 : 0;
            $i > 0 ? $delay = $delay : 0;

            $frame->setImageDispose($imageDispose);
            $frame->setImageDelay($delay);

            //composite the single images
            $frame->compositeImage($background, Imagick::COMPOSITE_DEFAULT, 0, 0);

            foreach($layerStack as $singleImage)
            {
                if($singleImage instanceof Imagick)
                {
                    $frame->compositeImage($singleImage, Imagick::COMPOSITE_DEFAULT, 0, 0);
                }
            }

            //add the complete frame to the stage
            $stage->addImage($frame);
            $stage->setImageIterations($numIterations);
            // reset delay
            $delay = $imageDelay;
        }

//        echo 'IMG: ' . $imgTime . "\n";
//        echo 'REC: ' . $recTime . "\n";
//        echo 'TXT: ' . $texTime . "\n";

        //complete the banner
        $gifpath = OUTPUT_DIR . '/' . $this->getOutputDir() . '/' . $this->getOutputFilename() . '.gif';

        // write the file to disk
        $animatedGif = $stage->writeImages($gifpath, true);

        // optimize result
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
    public function getNumFrames()
    {
        return $this->numFrames;
    }

    /**
     * @param int $frames
     */
    public function setNumFrames($frames)
    {
        $this->numFrames = $frames;
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
