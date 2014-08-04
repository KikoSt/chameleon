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
        $database = new Database();
        $text = new GfxText();

        $this->view = $this->setLayout('views/editor.phtml')->getView();

        $template = $database->fetchTemplateById($_REQUEST['id']);

        $container->setCompanyId($template['companyId']);
        $container->setAdvertiserId($template['advertiserId']);
        $container->setId($template['id']);

        $destDir = $container->getOutputDir();

        $container->setSource($template['template']);
        $container->parse();
        $container->setTarget('GIF');

        if(isset($_REQUEST['submit']))
        {
            $container->render();
        }

        $this->view->elements = $container->getElements();

	$filename = $this->getLatestFile($destDir);
	$filepath = str_replace('/var/www', '', $destDir);
	$this->view->gif = $filepath . '/' . $filename;

        $this->view->fontlist = $text->getFontListForOverview();

        if($this->view->gif === null)
        {
            $this->view->gif = $_SESSION['gif'];
        }

        $_SESSION['gif'] = $this->view->gif;

        return true;
    }

    public function display()
    {
        echo $this->view;
    }

    private function getLatestFile($path)
    {
        $latestCtime = 0;
        $latestFilename = '';

        $d = dir($path);
	// TODO: check for .. and .
        while (false !== ($entry = $d->read())) {
            $filepath = "{$path}/{$entry}";
            // could do also other checks than just checking whether the entry is a file
            if (is_file($filepath) && filectime($filepath) > $latestCtime)
            {
                $latestCtime = filectime($filepath);
                $latestFilename = $entry;
            }
        }

        return $latestFilename;
    }

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
