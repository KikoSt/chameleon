<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 01.08.14
 * Time: 08:50
 */

class SvgBuilder
{
    public function create($width, $height)
    {
        //create header
        $string = "<?xml version='1.0'?>";
        $string .= '<svg width="'.$width.'" height="'.$height.'" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">';
        $string .= '<g>{ELEMENTS}</g></svg>';
        return $string;
    }
}
