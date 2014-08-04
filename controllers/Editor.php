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

        $template = $connector->getTemplateById();

        $baseFilename = 'rtest_' . $template->getIdBannerTemplate();
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

        $container->setSource($filename);
        $container->parse();
        $container->setTarget('GIF');
        $container->render();

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
