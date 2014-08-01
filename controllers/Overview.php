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

    public function create()
    {
        $container = new GfxContainer();
        $database = new Database();
        $connector = new APIConnector();

        $connector->setAdvertiserId($this->getAdvertiserId());
        $connector->setCompanyId($this->getCompanyId());

        $container->setAdvertiserId($this->getAdvertiserId());
        $container->setCompanyId($this->getCompanyId());

        $view = $this->setLayout('views/overview.phtml')->getView();

        $templates = $database->fetchTemplates();
        // $templates = $connector->getTemplates();

        foreach($templates as $template)
        {
            $container->setId($template['id']);

            $destDir = $container->getOutputDir();

            $container->setSource($template['template']);
            $container->parse();
            $container->setTarget('GIF');
            $container->render();
        }

        // TODO: use given templates, NOT rendered files here.
        $previews = $this->getRenderedFiles($destDir . '/');

        $view->previews = $previews;

        return $view;
    }

    private function getRenderedFiles($destinationDir)
    {
        return glob($destinationDir . '*.gif');
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
