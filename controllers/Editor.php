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
        $view = $this->setLayout('views/editor.phtml')->getView();

        $view->gif = $_REQUEST['id'];

        if($view->gif === null)
        {
            $view->gif = $_SESSION['gif'];
        }

        $_SESSION['gif'] = $view->gif;

        return $view;
    }
} 