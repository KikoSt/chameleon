<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 23.07.14
 * Time: 11:38
 */

class Overview extends Controller
{
    private $path;

    public function create()
    {
        $container = new GfxContainer();
        $view = $this->setLayout('views/overview.phtml')->getView();

        $this->setCompany('exampleCompany');
        $this->setUser('Mustermann');

        $company = $this->getCompany();
        $user = $this->getUser();

        $this->path = "output/$company/";

        if(!is_dir($this->path))
        {
            if(!mkdir($this->path, 0777, true))
            {
                die($this->path.' mkdir failed');
            }
            chmod($this->path, 0777);
        }

        //get templates and parse them into a corresponding directory (company / user)
//        $templates = $this->fetchTemplates();
//        foreach($templates as $template)
//        {
//            $container->setSource($template);
//            $container->parse();
//            $container->setTarget('GIF');
//            $container->render();
//        }

        $view->templates = $this->getRenderedFiles();

        return $view;
    }

    private function fetchTemplates()
    {
        // fetch all templates depending on the user, company, category

        //TODO get templates from database depending on user, company and so on
        return glob('svg/*.svg');
    }

    private function getRenderedFiles()
    {
        return glob('output/*.gif');
    }

} 