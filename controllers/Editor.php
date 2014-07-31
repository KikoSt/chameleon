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
        $database = new Database();
        $text = new GfxText();

        $view = $this->setLayout('views/editor.phtml')->getView();

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

        $view->elements = $container->getElements();

	$filename = $this->getLatestFile($destDir);
	$filepath = str_replace('/var/www', '', $destDir);
	$view->gif = $filepath . '/' . $filename;

        $view->fontlist = $text->getFontListForOverview();

        if($view->gif === null)
        {
            $view->gif = $_SESSION['gif'];
        }

        $_SESSION['gif'] = $view->gif;

        return $view;
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
} 
