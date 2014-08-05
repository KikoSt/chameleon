<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 24.07.14
 * Time: 11:21
 */

class Editor extends Controller
{
    public function create()
    {
        $container = new GfxContainer();
        $connector = new APIConnector();
        $text = new GfxText();

        $view = $this->setLayout('views/editor.phtml')->getView();

        if(isset($_REQUEST['id']))
        {
            if(null !== $_REQUEST['id'])
            {
                $container->setCompanyId($_REQUEST['companyId']);
                $container->setAdvertiserId($_REQUEST['advertiserId']);
                $container->setId($_REQUEST['id']);
            }
        }

        if(null !== $container->getId())
        {
            $_SESSION['bannerTemplateId'] = $container->getId();
            $_SESSION['advertiserId'] = $container->getAdvertiserId();
            $_SESSION['companyId'] = $container->getCompanyId();
        }

        if(null === $container->getId())
        {
            $container->setId($_SESSION['bannerTemplateId']);
            $container->setAdvertiserId($_SESSION['advertiserId']);
            $container->setCompanyId($_SESSION['companyId']);
        }

        $connector->setBannerTemplateId($container->getId());

        // get the template by id from the REST-API
        $template = $connector->getTemplateById();

        // prepare the file name
        $baseFilename = 'rtest_' . $template->getIdBannerTemplate();
        $filename = $baseFilename . '.svg';
        $container->setOutputName($baseFilename);

        if(!file_exists(SVG_DIR . $filename))
        {
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
        }

        //render the gif for the overview
        $container->setSource($filename);
        $container->parse();

        if(!file_exists($container->getOutputDir() . '/' . $baseFilename . '.gif'))
        {
            $container->setTarget('GIF');
            $container->render();
        }

        // remove the temporary file
//        unlink(SVG_DIR . $filename);

        // view parameters
        $view->templateId = $container->getId();
        $view->advertiserId = $container->getAdvertiserId();
        $view->companyId = $container->getCompanyId();
        $view->gif = str_replace('var/www/', '', $container->getOutputDir()) . '/' . $baseFilename . '.gif';
        $view->elements = $container->getElements();
        $view->fontlist = $text->getFontListForOverview();

        return $view;
    }

    public function getCompanyId()
    {
        return $this->companyId;
    }

    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }

    public function getAdvertiserId()
    {
        return $this->advertiserId;
    }

    public function setAdvertiserId($advertiserId)
    {
        $this->advertiserId = $advertiserId;
    }
}
