<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 17.07.14
 * Time: 09:04
 */

interface Resizeable
{
    public function getCurrentWidth();
    public function getCurrentHeight();
    public function setNewWidth($width);
    public function setNewHeight($height);
    public function getNewWidth();
    public function getNewHeight();
    public function resize();
} 