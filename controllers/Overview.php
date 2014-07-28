<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 23.07.14
 * Time: 11:38
 */

class Overview extends Controller
{
    public function create()
    {
        $container = new GfxContainer();
        $database = new Database();

        $view = $this->setLayout('views/overview.phtml')->getView();

        $templates = $database->fetchTemplates();

        foreach($templates as $template)
        {
            $container->setCompany($template['company']);
            $container->setAdvertiser($template['advertiser']);
            $container->setId($template['id']);

            $destDir = $container->createDestinationDir();

            $this->clearOutputDirectory($destDir);

            $container->setSource($template['template']);
            $container->parse();
            $container->setTarget('GIF');
            $container->setOutputDestination($destDir);
            $container->render();
        }

        $view->templates = $this->getRenderedFiles($destDir);

        return $view;
    }

    private function getRenderedFiles($destinationDir)
    {
        return glob($destinationDir . '*.gif');
    }

    private function clearOutputDirectory($path, $erase=true)
    {
        if($erase)
        {
            $files = glob($path.'*.*');

            foreach($files as $file)
            {
                if(is_file($file))
                {
                    unlink($file);
                }
            }
        }
    }
} 