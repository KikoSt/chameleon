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
        $view = $this->setLayout('views/editor.phtml')->getView();

        $template = $database->fetchTemplateById($_REQUEST['id']);

        $container->setCompany($template['company']);
        $container->setAdvertiser($template['advertiser']);
        $container->setId($template['id']);

        $destDir = $container->createDestinationDir();

        $container->setSource($template['template']);
        $container->parse();
        $container->setTarget('GIF');
        $container->setOutputDestination($destDir);

        if(isset($_REQUEST['submit']))
        {
            $container->render();
        }

        $view->elements = $container->getElements();

        $view->gif = $destDir . $this->getLatestFile($destDir);

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