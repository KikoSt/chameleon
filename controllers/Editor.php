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
    private $templateId;

    public function create()
    {
        $container = new GfxContainer();
        $text      = new GfxText($container);
        $this->connector = new APIConnector();

        $this->view = $this->setLayout('views/editor.phtml')->getView();

        $this->connector->setAdvertiserId($this->getAdvertiserId());
        $this->connector->setCompanyId($this->getCompanyId());

        $auditUserId  = $this->getAuditUserId();
        $templateId   = getRequestVar('templateId');
        $companyId    = $this->getCompanyId();
        $advertiserId = $this->getAdvertiserId();

        if(!isset($auditUserId) || empty($auditUserId))
        {
            return false;
        }

        $this->templateId = $templateId;
        $this->companyId = $companyId;
        $this->advertiserId = $advertiserId;

        $container->setId($templateId);
        $container->setcompanyId($companyId);
        $container->setAdvertiserId($advertiserId);

        $template = $this->connector->getTemplateById($container->getId());

        $baseFilename = getPreviewFileName($template);
        $filename = $baseFilename . '.svg';

        // render gif for editor view
        $container->setCategoryId(0); // general, so ZERO here
        $container->setOutputName($baseFilename);
        $container->setSource($template->getSvgContent());
        $container->parse();
        $container->setPreviewMode(true);

        $gif = 'http://' . $_SERVER['SERVER_NAME'] . '/chameleon/output/' . $container->getOutputDir() . '/' . $baseFilename . '.gif';
        $swf = 'http://' . $_SERVER['SERVER_NAME'] . '/chameleon/output/' . $container->getOutputDir() . '/' . $baseFilename . '.swf';

        $this->view->previewPaths = $this->getPreviewPaths();

        // $this->view->premiumUser = false;

        if(isset($_REQUEST['advanced']))
        {
            if($_REQUEST['advanced'] == 'true') {
            $premiumUser = true;
            } else {
                $premiumUser = false;
            }
        }

        $premiumUser = false;

        // view parameters
        $this->view->width           = $container->getCanvasWidth();
        $this->view->height          = $container->getCanvasHeight();
        $this->view->premiumUser     = $premiumUser;
        $this->view->imageMap        = getImageMap($container, $premiumUser);
        $this->view->templateId      = $container->getId();
        $this->view->auditUserId     = $auditUserId;
        $this->view->advertiserId    = $container->getAdvertiserId();
        $this->view->companyId       = $container->getCompanyId();
        $this->view->gif             = $gif;
        $this->view->swf             = $swf;
        $this->view->container       = $container;
        $this->view->elements        = $container->getElements();
        $this->view->fontlist        = $text->getFontListForOverview();
        $this->view->cmeoRefOptions  = $this->getCmeoRefOptions();
        $this->view->cmeoLinkOptions = $this->getCmeoLinkOptions();
        $this->view->name            = $template->getName();
        $this->view->fileName        = $filename;
        $this->view->fileSize        = getRemoteFileSize($gif);
        $this->view->categories      = $this->connector->getCategories();
        // TODO: the same call is invoked twice here, once when calling connector->getSubscribedCategoriesByTemplateId,
        //       once within getSubscribedCategories
        //       Most likely, we wouldn't need this at all any longer since all "subscribed categories are provided
        //       along with the template
        $this->view->subscribedCategories = $this->connector->getSubscribedCategoriesByTemplateId($container->getId());
        $this->view->combinedCategories = $this->getSubscribedCategories($template);
        $this->view->activeCategories = $this->getActiveCategories($this->view->combinedCategories);
        $this->view->allowedDimensions = $this->connector->getAllowedBannerDimensions();

        $this->view->template = $template;

        $this->view->page = 'editor';

        $this->addSubscribedCategoriesToSession($this->view->combinedCategories);

        //TODO for development, replace after implementing into Bidder

        $container->setTarget('GIF');

        if(!empty($_REQUEST['action']))
        {
            $container->render();
        }

        $container->setTarget('SWF');
        $container->render();

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

    public function setAuditUserId($auditUserId)
    {
        $this->auditUserId = $auditUserId;
    }

    public function getAuditUserId()
    {
        return $this->auditUserId;
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
        $templateCategories   = $template->getCategorySubscriptions();
        $subscribedCategories = $this->connector->getSubscribedCategoriesByTemplateId($template->getBannerTemplateId());
        $combinedCategory     = array();

        $categoryNames = array();

        // preparing a list containing all relevant category names indexed by the category IDs
        foreach($subscribedCategories AS $curCategory)
        {
            $categoryNames[$curCategory->idCategory] = $curCategory->categoryName;
        }

        // the templateCategories collection only contains the current active state (DELETED, ACTIVE ...) and the category ID.
        // adding names here
        foreach($templateCategories as $singleCategory)
        {
            $aSingleCategory = array();
            $aSingleCategory['id']     = $singleCategory->idCategory;
            $aSingleCategory['status'] = $singleCategory->userStatus;
            $aSingleCategory['name']   = $singleCategory->categoryName;
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


    private function getPreviewPaths()
    {
        $filePaths = array();
        if($dirhandle = opendir('output/' . $this->companyId . '/' . $this->advertiserId . '/preview/' . $this->templateId))
        {
            while(false !== ($file = readdir($dirhandle)))
            {
                if($file !== '.' && $file !== '..')
                {
                    $filePaths[] = 'output/' . $this->companyId . '/' . $this->advertiserId . '/preview/' . $this->templateId . '/' . $file;
                }
            }
        }
        return $filePaths;
    }
}
