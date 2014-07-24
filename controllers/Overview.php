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
        $view = $this->setLayout('views/overview.phtml')->getView();

        $view->templates = $this->fetchTemplates();

        return $view;
    }

    public function fetchTemplates()
    {
        // fetch all templates depending on the user, company, category

        //TODO get templates from database depending on user, company and so on
        return glob('output/*.gif');
    }
} 