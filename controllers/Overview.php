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
        // create required objects
        $container = new GfxContainer();
        $connector = new APIConnector();
        $previews = array();

        $connector->setAdvertiserId($this->getAdvertiserId());
        $connector->setCompanyId($this->getCompanyId());

        $container->setAdvertiserId($this->getAdvertiserId());
        $container->setCompanyId($this->getCompanyId());
        $container->setCategoryId(0);
        $container->setPreviewMode(true);

        $this->view = $this->setLayout('views/overview.phtml')->getView();

        // get all templates for company / advertiser
        $templates = $connector->getTemplates();

        foreach($templates as $template)
        {
            $baseFilename = 'rtest_' . $template->getBannerTemplateId();
            $filename = $baseFilename . '.svg';
            $container->setOutputName($baseFilename);

            $container->setSource($template->getSvgContent());
            $container->setId($template->getBannerTemplateId());
            $container->saveSvg();

            $container->setTarget('GIF');
            $container->render();

            $preview = new StdClass();
            $preview->filepath = str_replace($_SERVER['DOCUMENT_ROOT'], '', OUTPUT_DIR . '/' . $container->getOutputDir()) . '/' . $baseFilename . '.gif';
            $preview->width = $container->getCanvasWidth() / 2 > 300 ? 300 : $container->getCanvasWidth() / 2;
            $preview->height = $container->getCanvasHeight();
            $preview->id = $template->getBannerTemplateId();
            $preview->templateName = $filename;
            $preview->templateId = $template->getBannerTemplateId();
            $preview->advertiserId = $this->getAdvertiserId();
            $preview->companyId = $this->getCompanyId();
            $previews[] = $preview;

            // unlink(SVG_DIR . $filename);
        }
        $this->view->previews = $previews;

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
