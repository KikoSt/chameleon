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
        $text      = new GfxText($container);

        $this->view = $this->setLayout('views/editor.phtml')->getView();

        $templateId   = getRequestVar('templateId');
        $companyId    = getRequestVar('companyId');
        $advertiserId = getRequestVar('advertiserId');

        $container->setId($templateId);
        $container->setcompanyId($companyId);
        $container->setAdvertiserId($advertiserId);

        $baseFilename = 'rtest_' . $container->getId();
        $filename = $baseFilename . '.svg';

        // render gif for editor view
        $container->setCategoryId(0);
        $container->setOutputName($baseFilename);
        $container->setSource($filename);
        $container->parse();
        $container->setPreviewMode(true);
        $container->setTarget('GIF');
        $container->render();

        // view parameters
        $this->view->templateId      = $container->getId();
        $this->view->id              = $container->getId();
        $this->view->advertiserId    = $container->getAdvertiserId();
        $this->view->companyId       = $container->getCompanyId();
        $this->view->gif             = str_replace('var/www/', '', OUTPUT_DIR . '/' . $container->getOutputDir()) . '/' . $baseFilename . '.gif';
        $this->view->elements        = $container->getElements();
        $this->view->width           = $container->getCanvasWidth();
        $this->view->height          = $container->getCanvasHeight();
        $this->view->fontlist        = $text->getFontListForOverview();
        $this->view->cmeoRefOptions  = $this->getCmeoRefOptions();
        $this->view->cmeoLinkOptions = $this->getCmeoLinkOptions();

        $container->setTarget('GIF');
        $container->render();

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
