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
    private $connector;

    public function create()
    {
        $container = new GfxContainer();
        $text      = new GfxText($container);
        $this->connector = new APIConnector();

        $this->view = $this->setLayout('views/editor.phtml')->getView();

        $this->connector->setAdvertiserId($this->getAdvertiserId());
        $this->connector->setCompanyId($this->getCompanyId());

        $templateId   = getRequestVar('templateId');
        $companyId    = getRequestVar('companyId');
        $advertiserId = getRequestVar('advertiserId');

        $container->setId($templateId);
        $container->setcompanyId($companyId);
        $container->setAdvertiserId($advertiserId);

        $template = $this->connector->getTemplateById($container->getId());

        $baseFilename = 'rtest_' . $container->getId();
        $filename = $baseFilename . '.svg';

        // render gif for editor view
        $container->setCategoryId(0); // general, so ZERO here
        $container->setOutputName($baseFilename);
        $container->setSource($filename);
        $container->parse();
        $container->setPreviewMode(true);

        $gif = 'http://' . $_SERVER['SERVER_NAME'] . '/chameleon/output/' . $container->getOutputDir() . '/' . $baseFilename . '.gif';

        // view parameters
        $this->view->templateId      = $container->getId();
        $this->view->advertiserId    = $container->getAdvertiserId();
        $this->view->companyId       = $container->getCompanyId();
        $this->view->gif             = $gif;
        $this->view->container       = $container;
        $this->view->elements        = $container->getElements();
        $this->view->fontlist        = $text->getFontListForOverview();
        $this->view->cmeoRefOptions  = $this->getCmeoRefOptions();
        $this->view->cmeoLinkOptions = $this->getCmeoLinkOptions();
        $this->view->name            = $template->getName();
        $this->view->fileName        = $filename;
        $this->view->fileSize        = getRemoteFileSize($gif);
        $this->view->categories      = $this->connector->getCategories();
        $subscribedCategories        = $this->connector->getSubscribedCategoriesByTemplateId($container->getId());

        $this->view->subscribedCategories = $subscribedCategories;

        $this->view->combinedCategories = $this->getSubscribedCategories($template);

        $this->view->activeCategories = $this->getActiveCategories($this->view->combinedCategories);

        $this->addSubscribedCategoriesToSession($this->view->combinedCategories);

        $this->view->page = 'editor';

        //TODO for development, replace after implementing into Bidder
        $this->view->premiumUser = false;

        $container->setTarget('GIF');

        if(!empty($_REQUEST['action']))
        {
            $container->render();
        }

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
        $array = array('description', 'name', 'productImageUrl', 'price', 'priceOld');
        return $array;
    }

    private function getCmeoLinkOptions()
    {
        $array = array('companyUrl', 'categoryUrl', 'productUrl');
        return $array;
    }

    private function addSubscribedCategoriesToSession($subscribedCategories)
    {
        $_SESSION['category'] = array();
        foreach($subscribedCategories as $singleCategory)
        {
            if($singleCategory['status'] === "ACTIVE")
            {
                $_SESSION['category'][$singleCategory['id']] = $singleCategory['name'];
            }
        }
    }

    private function getSubscribedCategories($template)
    {
        $templateCategories = $template->getCategorySubscriptions();
        $subscribedCategories = $this->connector->getSubscribedCategoriesByTemplateId($template->getBannerTemplateId());
        $combinedCategory = array();

        foreach($templateCategories as $singleCategory)
        {
            $aSingleCategory = array();
            $aSingleCategory['id'] = $singleCategory->idCategory;
            $aSingleCategory['status'] = $singleCategory->userStatus;

            foreach($subscribedCategories as $singleSubscription)
            {
                if($aSingleCategory['id'] === $singleSubscription->idCategory)
                {
                    $aSingleCategory['name'] = $singleSubscription->categoryName;
                }
            }
            $combinedCategory[] = $aSingleCategory;
        }

        return $combinedCategory;
    }

    private function getActiveCategories($subscribedCategories)
    {
        foreach($subscribedCategories as $id => $singleCategory)
        {
            if($singleCategory['status'] !== "ACTIVE")
            {
                unset($subscribedCategories[$id]);
            }
        }
        return $subscribedCategories;
    }
}
