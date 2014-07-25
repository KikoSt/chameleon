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
        $this->setUser('Mustermann');

        $path = 'tmp/'. $this->getCompany() .'/'. $this->getUser();

        if(!is_dir($path))
        {
            mkdir($path);
        }

        $templates = $this->fetchTemplates();

        foreach($templates as $template)
        {
            $container->setSource($template);
            $container->setTarget('output/' . $path);

        }

        return $view;
    }

    private function fetchTemplates()
    {
        // fetch all templates depending on the user, company, category

        //TODO get templates from database depending on user, company and so on
        return glob('svg/*.svg');
    }

} 