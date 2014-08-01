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
            $container->setId($template['id']);

	    $container->setCompanyId(4);
            $container->setAdvertiserId($template['advertiserId']);
            $destDir = $container->getOutputDir();

            $container->setSource($template['template']);
            $container->parse();
            $container->setTarget('GIF');
            $container->render();
        }

        // TODO: use given templates, NOT rendered files here.
        $previews = $this->getRenderedFiles($destDir . '/');

        $view->previews = $previews;

        return $view;
    }

    private function getRenderedFiles($destinationDir)
    {
        return glob($destinationDir . '*.gif');
    }

    private function clearOutputDirectory($path)
    {
        $files = glob($path . '*.*');

        foreach ($files as $file)
        {
            if (is_file($file))
            {
                unlink($file);
            }
        }
    }
}
