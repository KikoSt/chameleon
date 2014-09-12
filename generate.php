<?php

require_once('Bootstrap.php');
include('config/pathconfig.inc.php');

iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");

if(!defined('__ROOT__'))
{
    define('__ROOT__', './');
}

// get ini settings
try
{
    $iniSettings = parse_ini_file('generate.ini');
}
catch(Exception $e)
{
    echo 'Ini file not found, exiting';
    exit(1);
}

// set adequat error level
try
{
    error_reporting($iniSettings['reporting_level']);
}
catch(Exception $e)
{
    echo $e->getMessage();
    error_reporting(E_ALL);
}

error_reporting(E_ALL);
$generator = new CMEOGenerator($argv);
$generator->generate();


















class CMEOGenerator
{
    private $companyId;
    private $advertiserId;
    private $categoryId;
    private $auditUserId;

    private $connector;
    private $container;

    private $logHandle;

    private $dateformat;
    private $timeformat;

    public function __construct($argv)
    {
        $this->companyId    = (int) $argv[1];
        $this->advertiserId = (int) $argv[2];
        $this->categoryId   = (int) $argv[3];
        $this->auditUserId  = (int) $argv[4];

        $this->connector = new APIConnector();
        $this->container = new GfxContainer();

        $this->connector->setAdvertiserId($this->advertiserId);
        $this->connector->setCompanyId($this->companyId);
        $this->connector->setAuditUserId($this->auditUserId);

        $this->container->setAdvertiserId($this->advertiserId);
        $this->container->setCompanyId($this->companyId);
        $this->container->setCategoryId($this->categoryId);
        $this->container->setPreviewMode(true);

        // get ini settings
        try
        {
            $iniSettings = parse_ini_file('generate.ini');
        }
        catch(Exception $e)
        {
            echo 'Ini file not found, exiting';
            exit(1);
        }

        $this->templateFilterList = $iniSettings['templates'];

        if($iniSettings['log_target'] === 'file')
        {
            if(array_key_exists('log_file_name', $iniSettings))
            {
                $logfileName = $iniSettings['log_file_name'];
            }
            else
            {
                $logfileName = 'generate_logfile_<date>.log';
            }
            $this->prepareLogfile($logfileName);
        }

        $this->dateformat = $iniSettings['dateformat'];
        $this->timeformat = $iniSettings['timeformat'];
    }



    private function logMessage($message)
    {
        fwrite($this->logHandle, $message . "\n");
    }


    private function prepareLogFile($logfileName)
    {
        $datetime    = new Datetime();
        $dateStr     = $datetime->format($this->dateformat);
        $timeStr     = $datetime->format($this->timeformat);
        $dateTimeStr = $dateStr . ' ' . $timeStr;

        // process logfile name:
        $placeholders = array('<companyId>', '<advertiserId>', '<categoryId>', '<date>', '<datetime>');
        $replacements = array($this->companyId, $this->advertiserId, $this->categoryId, $dateStr, $dateTimeStr);
        $logfileName = str_replace($placeholders, $replacements, $logfileName);

        $specialChars = array(' ', '-', ':', '.', '\\', '/');
        $logfileName = str_replace($specialChars, '_', $logfileName);

        $logfileName .= '.log';

        $this->logHandle = fopen(LOG_DIR . '/' . $logfileName, 'a');
        if(!$this->logHandle)
        {
            throw new Exception('Could not open log file ' . LOG_DIR . '/' . $logfileName . '. Exiting');
        }
    }



    public function generate()
    {
        $datetime = new Datetime();
        $dateTimeStr = $datetime->format($this->dateformat . ' ' . $this->timeformat);
        $templates   = $this->connector->getTemplates();
        $productList = $this->connector->getProductsByCategory($this->categoryId);
        $count = 0;

        $logMessage = $dateTimeStr . "\n";
        $logMessage .= 'Processing templates for ';
        $logMessage .= 'companyId = ' . $this->companyId . ', ';
        $logMessage .= 'advertiserId = ' . $this->advertiserId . ', ';
        $logMessage .= 'categoryId = ' . $this->categoryId;
        $this->logMessage($logMessage);

        foreach($templates AS $template)
        {
            $this->container->setSource($template->getSvgContent());
            $this->container->setId($template->getBannerTemplateId());
            try
            {
                $this->container->parse();
            }
            catch(Exception $e)
            {
                $this->logMessage('An error occured: ' . $e->getMessage() . "\n");
                continue;
            }

            foreach($productList AS $product)
            {
                $this->container->setProductData($product);
                // foreach($this->iniSettings['formats'] AS $format)
                foreach(array('GIF', 'SWF') AS $format)
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
                    }
                    $message = 'Generated banner ' . $this->container->getOutputFilename() . ' (' . $format . ')' ;
                    $this->logMessage($message);
                }
                // $this->container->cleanup();
                $count++;
            }
        }
    }
}
