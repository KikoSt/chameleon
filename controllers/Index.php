<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 24.07.14
 * Time: 15:55
 */
class Index extends Controller
{
    public function getRedirect($page)
    {
        $modules = array('overview', 'editor');

        if(in_array($page, $modules))
        {
            switch($page)
            {
                case "overview":
                {
                    return new Overview();
                    break;
                }
                case "editor":
                {
                    return new Editor();
                    break;
                }
            }
        }
    }
}