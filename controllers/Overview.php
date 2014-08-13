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
        $container = new GfxContainer();
        $connector = new APIConnector();
        $previews = array();

        $connector->setAdvertiserId($this->getAdvertiserId());
        $connector->setCompanyId($this->getCompanyId());

        $container->setAdvertiserId($this->getAdvertiserId());
        $container->setCompanyId($this->getCompanyId());

        $this->view = $this->setLayout('views/overview.phtml')->getView();

        $templates = $connector->getTemplates();

        foreach($templates as $template)
        {
            Debug::browser($template->getSvgContent(),true);

            $baseFilename = 'rtest_' . $template->getBannerTemplateId();
            $filename = $baseFilename . '.svg';
            $container->setOutputName($baseFilename);

            // write the temporary file
            if(is_dir(SVG_DIR))
            {
                $fh = fopen(SVG_DIR . $filename, 'w');
                fwrite($fh, $template->getSvgContent());
                fclose($fh);
            }
            else
            {
                throw new Exception(SVG_DIR . ' not found !');
            }

            $container->setId($template->getBannerTemplateId());

            $container->setSource($filename);
            $container->parse();
            $container->setTarget('GIF');
            $container->render();

            $preview = new StdClass();
            $preview->filepath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $container->getOutputDir()) . '/' . $baseFilename . '.gif';
            $preview->width = $container->getCanvasWidth() / 2 > 300 ? 300 : $container->getCanvasWidth() / 2;
            $preview->height = $container->getCanvasHeight();
            $preview->id = $template->getBannerTemplateId();
            $preview->templateName = $filename;
            $preview->advertiserId = $this->getAdvertiserId();
            $preview->companyId = $this->getCompanyId();
            $previews[] = $preview;

            unlink(SVG_DIR . $filename);
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
