<?php

/**
 * CMEOGenerator
 *
 * Generate creatives/banner based on the following information:
 *
 * - one or more banner templates
 * - one or more category id's
 *
 * If more than one banner template is stored in the templateList,
 * the generator will create banners for all categories that are
 * stored in the categoryList AND associated with the banner template
 * itself.
 *
 * Example:
 * bannerTemplateList = [1, 8, 37, 96]
 * categoryList = [1234, 2345, 3456]
 *
 * categories associated with template 1:
 * 1024, 1234, 3210, 3456, 5432
 * ==> creatives for the categories 1234 and 3456 will be created
 *
 * categories associated with template 8:
 * 1000, 1234, 2345
 * ==> creatives for the categories 1234 and 2345 will be created
 *
 * categories associated with template 37:
 * 1234, 2345, 3456
 * ==> creatives for the categories 1234, 2345 and 3456 will be created
 *
 * categories associated with template 96:
 * 1111, 2222, 3333, 4444
 * ==> no creatives will be created
 *
 * so the resulting creatives will be:
 * [1/1234], [1/3456], [8/1234], [8/2345], [37/1234], [37/2345], [37/3456]
 *
 * OF COURSE the Generator can be also used with only one templateId and/or only one categoryId
 *
 * @package
 * @version $id$
 * @copyright 2014 Media Decision GmbH
 * @author Christoph 'Kiko' Starkmann <christoph.starkmann@mediadecision.com>
 * @license Proprietary/Closed Source
 */
class CMEOGenerator
{
    private $companyId;
    private $advertiserId;
    private $auditUserId;

    private $connector;
    private $container;

    private $logHandle;

    private $dateformat;
    private $timeformat;

    private $templateList;
    private $categoryList;

    public function __construct($argv)
    {
        $this->companyId    = (int) $argv[1];
        $this->advertiserId = (int) $argv[2];
        // $this->auditUserId  = (int) $argv[4];

        $this->connector = new APIConnector();
        $this->container = new GfxContainer();

        $this->connector->setAdvertiserId($this->advertiserId);
        $this->connector->setCompanyId($this->companyId);
        $this->connector->setAuditUserId($this->auditUserId);

        $this->container->setAdvertiserId($this->advertiserId);
        $this->container->setCompanyId($this->companyId);
        $this->container->setPreviewMode(false);

        // get ini settings
        try
        {
            $this->iniSettings = parse_ini_file('../generate.ini');
        }
        catch(Exception $e)
        {
            echo 'Ini file not found, exiting';
            exit(1);
        }

        $this->templateFilterList = $this->iniSettings['templates'];

        $this->templateList = array();
        $this->categoryList = array();

        $this->dateformat = $this->iniSettings['dateformat'];
        $this->timeformat = $this->iniSettings['timeformat'];
    }



    public function logMessage($message)
    {
        try
        {
            fwrite($this->logHandle, $message . "\n");
        }
        catch(Exception $e)
        {
            // for now
        }
    }


    public function getContainer()
    {
        return $this->container;
    }


    public function prepareLogFile($categoryId)
    {
        if($this->iniSettings['log_target'] === 'file')
        {
            if(array_key_exists('log_file_name', $this->iniSettings))
            {
                $logfileName = $this->iniSettings['log_file_name'];
            }
            else
            {
                $logfileName = 'generate_logfile_<date>.log';
            }
        }
        else
        {
            throw new FileAccessFailedException ('ini settings not found');
        }

        $datetime    = new Datetime();
        $dateStr     = $datetime->format($this->dateformat);
        $timeStr     = $datetime->format($this->timeformat);
        $dateTimeStr = $dateStr . ' ' . $timeStr;

        // process logfile name:
        $placeholders = array('<companyId>', '<advertiserId>', '<categoryId>', '<date>', '<datetime>');
        $replacements = array($this->companyId, $this->advertiserId, $categoryId, $dateStr, $dateTimeStr);
        $logfileName = str_replace($placeholders, $replacements, $logfileName);

        $specialChars = array(' ', '-', ':', '.', '\\', '/');
        $logfileName = str_replace($specialChars, '_', $logfileName);

        $logfileName .= '.log';

        $this->logHandle = fopen(LOG_DIR . '/' . $logfileName, 'a');
        if(!$this->logHandle)
        {
            throw new FileAccessFailedException('Could not open log file ' . LOG_DIR . '/' . $logfileName . '. Exiting');
        }
    }



    public function generate()
    {
        $datetime = new Datetime();
        $dateTimeStr = $datetime->format($this->dateformat . ' ' . $this->timeformat);

        $templates = array();

        foreach($this->templateList AS $curTemplateId)
        {
            $templates[] = $this->connector->getTemplateById($curTemplateId);
        }

        foreach($templates AS $template)
        {
            $categorySubscriptions = $template->getCategorySubscriptions();
            $selectedCategories = $this->getCategories();
            $subscriptionList = array();
            foreach($categorySubscriptions AS $categorySubscription)
            {
                if($categorySubscription->userStatus === 'ACTIVE')
                {
                    $subscriptionList[] = (int) $categorySubscription->idCategory;
                }
            }
            $categories = array_intersect($this->categoryList, $subscriptionList);
            $categories = $subscriptionList;

            $categories = $this->getCategories();

            foreach($categories AS $categoryId)
            {
                $this->prepareLogfile($categoryId);
                $this->container->setCategoryId($categoryId);

                $productList = $this->connector->getProductsByCategory($categoryId);
                $count = 0;

                $logMessage = $dateTimeStr . "\n";
                $logMessage .= 'Processing templates for ';
                $logMessage .= 'companyId = ' . $this->companyId . ', ';
                $logMessage .= 'advertiserId = ' . $this->advertiserId . ', ';
                $logMessage .= 'categoryId = ' . $categoryId;
                $this->logMessage($logMessage);

                $this->container->setSource($template->getSvgContent());
                $this->container->setId($template->getBannerTemplateId());
                try
                {
                    $this->container->parse();
                }
                catch(Exception $e)
                {
//                    $this->logMessage('An error occured: ' . $e->getMessage() . "\n");
                    continue;
                }

                foreach($productList AS $product)
                {
                    $this->render($product);
                    $count++;
                }
            }
        }
    }


    /**
     * render
     *
     * do the actual rendering regardless of the source of the product data
     *
     * @param mixed $product
     * @access private
     * @return void
     */
    public function render($product, $format='ALL')
    {
        $formats = array('GIF', 'SWF');
        if(!in_array($format, $formats))
        {
            $format === 'ALL';
        }

        if($format !== 'ALL')
        {
            $formats = array($format);
        }

        $this->container->setProductData($product);

        // foreach($this->iniSettings['formats'] AS $format)
        foreach($formats AS $format)
        {
            $this->container->setTarget($format);

            try
            {
                $this->container->render();
            }
            catch(Exception $e)
            {
                $message = 'An error occured trying to render banner ' . $this->container->getOutputFilename();
                $message .= ', current output format ' . $format . ': ' . $e->getMessage();
                $this->logMessage($message);

                continue;
            }

            $message = 'Generated banner ' . $this->container->getOutputFilename() . ' (' . $format . ')' ;
            $this->logMessage($message);
        }
        // $this->container->cleanup();
    }

    /**
     * setCategories
     *
     * $categories can either be:
     * - integer --- a specific category id
     * - array   --- a list of integers / category id's
     * - 'ALL'   --- constant - use ALL categories associated with this template
     *
     * @param mixed $categories
     * @access public
     * @return void
     */
    public function setCategories($categoryList)
    {
        if($categoryList === 'ALL')
        {
            $this->categoryList[] = 'ALL';
        }
        else if(!is_array($categoryList))
        {
            if(!is_numeric($categoryList))
            {
                return false;
            }
            else
            {
                $this->categoryList[] = $categoryList;
            }
        } else {
            $this->categoryList = array_merge($this->categoryList, $categoryList);
        }
    }

    public function getCategories()
    {
        return $this->categoryList;
    }

    /**
     * addTemplates
     *
     * $templates can either be:
     * - integer --- a specific template id
     * - array   --- a list of integers (template id's)
     * - 'ALL'   --- constant - use ALL templates from currently set company and advertiser
     * @param mixed $templateList
     * @access public
     * @return void
     */
    public function addTemplates($templateList)
    {
        if(!is_array($templateList))
        {
            if(!is_numeric($templateList))
            {
                return false;
            }
            else
            {
                $templateList = array($templateList);
            }
        }
        $this->templateList = array_merge($this->templateList, $templateList);
        return true;
    }

    /**
     * setTemplates
     *
     * wrapper method for addTemplates
     *
     * @param mixed $templateList
     * @access public
     * @return void
     */
    public function setTemplates($templateList)
    {
        $this->addTemplates($templateList);
    }
}

