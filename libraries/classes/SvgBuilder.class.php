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
        //creates the complete svg file
        //todo parameters are an temporary develop hook
        $this->createFrame($width, $height);
    }

    public function createRectangleNode()
    {
//    <rect
//    fill="#f98901"
//    stroke="#000000"
//    x="0"
//    y="0"
//    width="970"
//    height="250"
//    id="background"/>
    }

    private function createFrame($width, $height)
    {
        //create header
        $string = "<?xml version='1.0'?>\n";
        $string .= '<svg width="'.$width.'" height="'.$height.'" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">\n';
        $string .= '<g>{ELEMENTS}</g></svg>';
        return $string;
    }
}
