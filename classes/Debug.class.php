<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 17.07.14
 * Time: 14:38
 */

class Debug
{
    public static function browser($value, $bExit = false)
    {
        echo("<pre>");
        var_dump($value);
        echo("</pre>");

        if($bExit)
        {
            die();
        }
    }

    public static function console($elements)
    {
        print_r($elements);
    }
}
