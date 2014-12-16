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

        // for now, we're simply including this ajax script here
        // until there is a decided "final" solution to the file size
        // issue
        require_once('ajax/getSizeLimits.php');

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
        $container->setTarget('GIF');
        $container->setPreviewMode(true);
        $container->animatePreviews(true);

        // the preview already exists from the overview - either the animated version OR the non-animated one; Using this preview
        // and recreating it in order to get an animated version once the editor has completely loaded

        $gif = 'http://' . $_SERVER['SERVER_NAME'] . '/chameleon/output/' . $container->getOutputDir() . '/' . $baseFilename . '.gif';
        $swf = 'http://' . $_SERVER['SERVER_NAME'] . '/chameleon/output/' . $container->getOutputDir() . '/' . $baseFilename . '.swf';

        $this->view->previewPaths = $this->getPreviewPaths();

        if(isset($_REQUEST['advanced']))
        {
            if($_REQUEST['advanced'] == 'true') {
            $premiumUser = true;
            } else {
                $premiumUser = false;
            }
        }

        $premiumUser = true;
        $premiumUser = false;

        // view parameters
        $this->view->width           = $container->getCanvasWidth();
        $this->view->height          = $container->getCanvasHeight();
        $this->view->premiumUser     = $premiumUser;
        $this->view->imageMap        = getImageMap($container, $premiumUser);
        $this->view->templateId      = $container->getId();
        $this->view->parentTemplateId = $template->getParentBannerTemplateId();
        $this->view->auditUserId     = $auditUserId;
        $this->view->advertiserId    = $container->getAdvertiserId();
        $this->view->companyId       = $container->getCompanyId();
        $this->view->gif             = $gif;
        $this->view->swf             = $swf;
        $this->view->container       = $container;
        $this->view->elements        = $container->getElements();
        $this->view->fontlist        = $text->getFontListForOverview();

        $fontsizeList                = array();
        for($i=6; $i<42; $i++)
        {
            $fontsizeList[] = $i;
        }
        $this->view->fontsizeList    = $fontsizeList;

        $this->view->cmeoRefOptions  = $this->getCmeoRefOptions();
        $this->view->cmeoLinkOptions = $this->getCmeoLinkOptions();
        $this->view->name            = $template->getName();
        $this->view->fileName        = $filename;
        $this->view->gifFileSize     = getRemoteFileSize($gif);
        $this->view->swfFileSize     = getRemoteFileSize($swf);

        $format = $container->getCanvasWidth() . 'x' . $container->getCanvasHeight();

        if($this->view->gifFileSize > $gifSizeLimits[$format])
        {
            $this->view->gifFileSizeWarning = true;
        }
        else
        {
            $this->view->gifFileSizeWarning = false;
        }

        if($this->view->swfFileSize > $swfSizeLimits[$format])
        {
            $this->view->swfFileSizeWarning = true;
        }
        else
        {
                $this->view->swfFileSizeWarning = false;
        }

        $this->view->categories      = $this->connector->getCategories();
        // TODO: the same call is invoked twice here, once when calling connector->getSubscribedCategoriesByTemplateId,
        //       once within getSubscribedCategories
        //       Most likely, we wouldn't need this at all any longer since all "subscribed categories are provided
        //       along with the template
        $this->view->combinedCategories   = $this->getSubscribedCategories($template);
        $this->view->activeCategories     = $this->getActiveCategories($this->view->combinedCategories);
        $this->view->allowedDimensions    = $this->connector->getAllowedBannerDimensions();

        //todo the following two calls deliver the same result, figure out if this is necessary
        $this->view->templateSubscription = $template->getCategorySubscriptions();
        $this->view->subscribedCategories = $this->connector->getSubscribedCategoriesByTemplateId($container->getId());

        $this->view->availableCategories = getPrunedAvailableCategories($this->view->categories , $this->view->templateSubscription);

        $this->view->template = $template;

        $this->view->page = 'editor';

        if($template->getDateModified() === $template->getDateCreate())
        {
            $this->view->unModified = true;
        }
        else
        {
            $this->view->unModified = false;
        }

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
        $options = array();
        $options['GfxText'] = array('description', 'name', 'price', 'priceOld');
        $options['GfxImage'] = array('imageUrl');
        return $options;
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
        // TODO: problematic if directory doesn't exist ...
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
