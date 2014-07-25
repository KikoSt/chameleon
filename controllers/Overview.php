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
        $view = $this->setLayout('views/overview.phtml')->getView();



        $this->setCompany('exampleCompany');
        $this->setAdvertiser('Mustermann');

        $company = $this->getCompany();
        $advertiser = $this->getAdvertiser();

        $destinationDir = $container->createDestinationDir("$company/$advertiser");

        $this->clearOutputDirectory($destinationDir);

        $templates = $this->fetchTemplates();

        foreach($templates as $template)
        {
            $container->setSource($template);
            $container->parse();
            $container->setTarget('GIF');
            $container->setOutputDestination($destinationDir);
            $container->render();
        }

        $view->templates = $this->getRenderedFiles($destinationDir);

        return $view;
    }

    private function fetchTemplates()
    {
        // fetch all templates depending on the user, company, category

        //TODO get templates from database depending on user, company and so on
        return glob('svg/*.svg');
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