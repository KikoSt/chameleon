<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 24.07.14
 * Time: 11:21
 */

class Editor extends Controller
{
    private $companyId;
    private $advertiserId;
    private $view;

    public function create()
    {
        $container = new GfxContainer();
        $connector = new APIConnector();
        $text = new GfxText();

        $this->view = $this->setLayout('views/editor.phtml')->getView();

        $container->setId(getRequestVar('id'));
        $container->setcompanyId(getRequestVar('companyId'));
        $container->setAdvertiserId(getRequestVar('advertiserId'));

        if(null !== $container->getId())
        {
            $_SESSION['bannerTemplateId'] = $container->getId();
            $_SESSION['advertiserId'] = $container->getAdvertiserId();
            $_SESSION['companyId'] = $container->getCompanyId();
        }
        else
        {
            $container->setId($_SESSION['bannerTemplateId']);
            $container->setAdvertiserId($_SESSION['advertiserId']);
            $container->setCompanyId($_SESSION['companyId']);
        }

        $connector->setBannerTemplateId($container->getId());

        // get the template by id from the REST-API
        $template = $connector->getTemplateById();

        // prepare the file name
        $baseFilename = 'rtest_' . $template->getBannerTemplateId();
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

        $this->view->templateId = $container->getId();
        $this->view->gif = str_replace('var/www/', '', $container->getOutputDir()) . '/' . $baseFilename . '.gif';
        $this->view->elements = $container->getElements();
        $this->view->fontlist = $text->getFontListForOverview();

        unlink(SVG_DIR . $filename);

        return true;
    }

    public function display()
    {
        echo $this->view;
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
