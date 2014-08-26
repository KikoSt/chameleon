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
        $text = new GfxText($container);

        $this->view = $this->setLayout('views/editor.phtml')->getView();

        $templateId   = getRequestVar('templateId');
        $companyId    = getRequestVar('companyId');
        $advertiserId = getRequestVar('advertiserId');

        $container->setId($templateId);
        $container->setcompanyId($companyId);
        $container->setAdvertiserId($advertiserId);

        // WTF!
        // No, no, no!
        // If the id's aren't provided, the script would already have been terminated; Those information MUST
        // be provided
        // Information should not be stored based on assumptions but we should know the state and react adequately

        // all three ID's, the templateId, advertiserId AND companyId should be included in EVERY call!
        // $_SESSION['templateId']   = $templateId;
        // $_SESSION['advertiserId'] = $advertiserId;
        // $_SESSION['companyId']    = $companyId;

        $basePath = (string) $this->getCompanyId() . '/' . (string) $this->getAdvertiserId() . '/';

        // check if svg_dir exists
        if(is_dir(SVG_DIR . $basePath))
        {
            // prepare the file name
            $baseFilename = 'rtest_' . $container->getId();
            $filename = $baseFilename . '.svg';

            // check if file with id already exists
            if(!file_exists(SVG_DIR . $basePath . $filename))
            {
                // get template by id
                $template = $connector->getTemplateById($container->getId());

                // create svg
                $handle = fopen(SVG_DIR . $basePath . $filename, 'w');
                if(!$handle)
                {
                    throw new Exception('Could not open file ' . SVG_DIR . $filename);
                }
                fwrite($handle, $template->getSvgContent());
                fclose($handle);
            }

            // render gif for editor view
            $container->setCategoryId(0);
            $container->setOutputName($baseFilename);
            $container->setSource($basePath . $filename);
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
        $this->view->cmeoRefOptions = $this->getCmeoRefOptions();
        $this->view->cmeoLinkOptions = $this->getCmeoLinkOptions();

        if(!file_exists($container->getOutputDir() . '/' . $baseFilename . '.gif'))
        {
            $container->setTarget('GIF');
            $container->render();
        }

        // unlink(SVG_DIR . $filename);

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

    private function getCmeoRefOptions()
    {
        $array = array('description', 'name', 'productImageUrl', 'productImageUrl', 'price', 'priceOld');

        return $array;
    }

    private function getCmeoLinkOptions()
    {
        $array = array('companyUrl', 'categoryUrl', 'productUrl');
        return $array;
    }
}
