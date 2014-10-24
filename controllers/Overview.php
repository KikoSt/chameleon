<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 23.07.14
 * Time: 11:38
 */

class Overview extends Controller
{
    private $advertiserId;
    private $companyId;
    private $view;

    /**
     * @return TemplateEngine|void
     * @throws Exception
     */
    public function create()
    {
        $_SESSION['categories'] = array();

        // create required objects
        $container = new GfxContainer();
        $connector = new APIConnector();
        $templates = array();
        $previews  = array();
        $loadError = false;

        $connector->setAdvertiserId($this->getAdvertiserId());
        $connector->setCompanyId($this->getCompanyId());

        $container->setAdvertiserId($this->getAdvertiserId());
        $container->setCompanyId($this->getCompanyId());
        $container->setCategoryId(0);
        $container->setPreviewMode(true);

        $this->view = $this->setLayout('views/overview_proto.phtml')->getView();
        $this->view->advertiserId = $this->getAdvertiserId();

        // get all templates for company / advertiser
        try
        {
            $templates = $connector->getTemplates();
        }
        catch(Exception $e)
        {
            $this->view->message = 'An error occured: ' . $e->getMessage();
            $loadError = true;
        }

        if(!$loadError)
        {
            if(count($templates) == 0)
            {
                $this->view->message = 'No templates found!';
            }
            else
            {
                $categories = $connector->getCategories();

                foreach($templates as $template)
                {
                    $baseFilename = getPreviewFileName($template);
                    $filename = $baseFilename . '.svg';
                    $container->setOutputName($baseFilename);

                    $container->setSource($template->getSvgContent());
                    $container->setId($template->getBannerTemplateId());

                    try
                    {
                        $container->parse();
                    }
                    catch(Exception $e)
                    {
                        continue;
                    }

                    $container->saveSvg();

                    $container->setTarget('GIF');
                    $container->render();

                    $file = BASE_DIR . "/output/" . $container->getOutputDir() . '/' . $baseFilename . '.gif';

                    $preview = new StdClass();
                    $preview->filePath = $file;
                    $preview->templateName = $filename;
                    $preview->templateId = $template->getBannerTemplateId();
                    $preview->advertiserId = $this->getAdvertiserId();
                    $preview->companyId = $this->getCompanyId();
                    $preview->fileSize = getRemoteFileSize($file);
                    $preview->dateCreate = date("Y-m-d H:i:s", parseJavaTimestamp($template->getDateCreate()));
                    $preview->dateModified = date("Y-m-d H:i:s", parseJavaTimestamp($template->getDateModified()));
                    $preview->templateId = $template->getBannerTemplateId();
                    $preview->parentTemplateId = $template->getParentBannerTemplateId();
                    $preview->shortDescription = $this->getShortenedDescription($template->getName());
                    $preview->description = $template->getName();
                    $preview->templateSubscription = $template->getCategorySubscriptions();
                    $preview->availableCategories = $this->getPrunedAvailableCategories($categories, $preview->templateSubscription);
                    $preview->bannerDimension = $this->getBannerDimension($container);


                    if((int)$container->getCanvasWidth() > (int)$container->getCanvasHeight())
                    {
                        if($container->getCanvasHeight() > 210)
                        {
                            $preview->margin = ($container->getCanvasHeight() - 210)/2;
                        }
                        else
                        {
                            $preview->margin = (210 - $container->getCanvasHeight())/2;
                        }

                    }


                    $previews[] = $preview;
                }
            }
        }

        $this->view->previews = $previews;
        $this->view->page = 'overview';

        return $this->view;
    }

    public function display()
    {
        echo $this->view;
    }

    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }

    private function getBannerDimension(GfxContainer $container)
    {
        $connector = new APIConnector();
        $allowedBannerDimensions = $connector->getAllowedBannerDimensions();

        $width = $container->getCanvasWidth();
        $height = $container->getCanvasHeight();

        foreach($allowedBannerDimensions as $allowedDimension)
        {
            if($width == $allowedDimension->width && $height == $allowedDimension->height)
            {
                return $allowedDimension;
            }
            else
            {
                $nonDefault = new stdClass();
                $nonDefault->width = $width;
                $nonDefault->height = $height;
                $nonDefault->name = 'Non-standard format';

                return $nonDefault;
            }
        }
    }

    private function getShortenedDescription($description)
    {
        if(str_word_count($description, 0) == 1)
        {
            $description = substr($description, 0, 20) . '...';
        }
        else if(strlen($description) > 70)
        {
            $description = substr($description, 0, 70) . '...';
        }

        return $description;
    }

    private function getPrunedAvailableCategories($categories, $templateSubscriptions)
    {
        $prunedCategories = array();

        foreach($categories as $category)
        {
            $prunedCategories[$category->id] = $category->name;
        }

        foreach($templateSubscriptions as $subscription)
        {
            if($subscription->userStatus === "ACTIVE")
            {
                unset($prunedCategories[$subscription->idCategory]);
            }
        }
        return $prunedCategories;
    }

    public function getCompanyId()
    {
        return $this->companyId;
    }

    public function setAdvertiserId($advertiserId)
    {
        $this->advertiserId = $advertiserId;
    }

    public function getAdvertiserId()
    {
        return $this->advertiserId;
    }

    public function setAuditUserId($auditUserId)
    {
        $this->auditUserId = $auditUserId;
    }

    public function getAuditUserId()
    {
        return $this->auditUserId;
    }
}
