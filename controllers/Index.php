<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 24.07.14
 * Time: 15:55
 */
class Index extends Controller
{
    public function getController($page)
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
            case "manage":
            {
                return new Manager();
                break;
            }
            default:
            {
                throw new Exception('No applicable module found');
            }
        }
    }
}
