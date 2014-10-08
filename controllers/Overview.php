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

        $this->view = $this->setLayout('views/overview.phtml')->getView();

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
                foreach($templates as $template)
                {
                    $baseFilename = 'rtest_' . $template->getBannerTemplateId();
                    $filename = $baseFilename . '.svg';
                    $container->setOutputName($baseFilename);

                    $container->setSource($template->getSvgContent());
                    $container->setId($template->getBannerTemplateId());
                    $container->parse();
                    $container->saveSvg();

                    $container->setTarget('GIF');
                    $container->render();

                    $file = BASE_DIR . "/output/" . $container->getOutputDir() . '/' . $baseFilename . '.gif';

                    $preview = new StdClass();
                    $preview->filePath = $file;
                    $preview->bannerWidth = $container->getCanvasWidth();
                    $preview->bannerHeight = $container->getCanvasHeight();
                    $preview->width = $container->getCanvasWidth() / 2 > 300 ? 300 : $container->getCanvasWidth() / 2;
                    $preview->height = $container->getCanvasHeight();
                    $preview->templateName = $filename;
                    $preview->templateId = $template->getBannerTemplateId();
                    $preview->advertiserId = $this->getAdvertiserId();
                    $preview->companyId = $this->getCompanyId();
                    $preview->fileSize = getRemoteFileSize($file);
                    $preview->dateCreate = date("Y-m-d H.i:s", parseJavaTimestamp($template->getDateCreate()));
                    $preview->dateModified = date("Y-m-d H.i:s", parseJavaTimestamp($template->getDateModified()));
                    $preview->parentTemplateId = $template->getParentBannerTemplateId();
                    $preview->name = $template->getName();
                    $preview->categorySubscription = $connector->getSubscribedCategoriesByTemplateId($template->getBannerTemplateId());
                    $preview->templateSubsriptions = $template->getCategorySubscriptions();


                    if($container->getCanvasWidth() >= $container->getCanvasHeight())
                    {
                        $newHeight = $container->getCanvasHeight() * (281 / $container->getCanvasWidth());
                        $preview->marginTop = (481 - intval($newHeight)) / 4;
                    }
                    else
                    {
                        $preview->marginTop = 4;
                    }
                    $previews[] = $preview;
                }
            }
        }

        $this->view->previews = $previews;
        $this->view->page = 'overview';
        $this->view->categories = $connector->getCategories();

        return $this->view;
    }

    public function display()
    {
        echo $this->view;
    }

//    private function clearOutputDirectory($path)
//    {
//        $files = glob($path . '*.*');
//
//        foreach ($files as $file)
//        {
//            if (is_file($file))
//            {
//                unlink($file);
//            }
//        }
//    }

    /**
     * Get companyId.
     *
     * @return companyId.
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * Set companyId.
     *
     * @param companyId the value to set.
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * Get advertiserId.
     *
     * @return advertiserId.
     */
    public function getAdvertiserId()
    {
        return $this->advertiserId;
    }

    /**
     * Set advertiserId.
     *
     * @param advertiserId the value to set.
     */
    public function setAdvertiserId($advertiserId)
    {
        $this->advertiserId = $advertiserId;
    }


}
