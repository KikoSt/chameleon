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

        if(isset($_REQUEST['templateId']))
        {
            if(null !== $_REQUEST['templateId'])
            {
                $container->setCompanyId($_REQUEST['companyId']);
                $container->setAdvertiserId($_REQUEST['advertiserId']);
                $container->setId($_REQUEST['templateId']);
            }
        }

        if(null !== $container->getId())
        {
            $_SESSION['templateId'] = $container->getId();
            $_SESSION['advertiserId'] = $container->getAdvertiserId();
            $_SESSION['companyId'] = $container->getCompanyId();
        }
        else
        {
            $container->setId($_SESSION['templateId']);
            $container->setAdvertiserId($_SESSION['advertiserId']);
            $container->setCompanyId($_SESSION['companyId']);
        }

        // check if svg_dir exists
        if(is_dir(SVG_DIR))
        {
            // prepare the file name
            $baseFilename = 'rtest_' . $container->getId();
            $filename = $baseFilename . '.svg';

            // check if file with id already exists
            if(!file_exists(SVG_DIR . $filename))
            {
                // get template by id
                $template = $connector->getTemplateById($container->getId());
                $container->setOutputName($baseFilename);

                // create svg
                $fh = fopen(SVG_DIR . $filename, 'w');
                fwrite($fh, $template->getSvgContent());
                fclose($fh);
            }

            // render gif for editor view
            $container->setSource($baseFilename);
            $container->parse();
            $container->setTarget('GIF');
            $container->render();
        }
        else
        {
            throw new Exception(SVG_DIR . ' not found !');
        }

        // view parameters
        $this->view->templateId = $container->getId();
        $this->view->advertiserId = $container->getAdvertiserId();
        $this->view->companyId = $container->getCompanyId();
        $this->view->gif = str_replace('var/www/', '', $container->getOutputDir()) . '/' . $baseFilename . '.gif';
        $this->view->elements = $container->getElements();
        $this->view->fontlist = $text->getFontListForOverview();

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
