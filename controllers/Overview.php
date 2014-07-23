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
        $view = $this->setTemplate('views/overview.php')->getView();

        Debug::console(glob('output/*.gif'));

        $view->templates = glob('output/*.gif');

        return $view;
    }
} 