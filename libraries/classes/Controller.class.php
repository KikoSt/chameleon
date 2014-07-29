<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 23.07.14
 * Time: 11:39
 */

class Controller
{
    private $template;

    public function __construct()
    {

    }

    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    public function getView()
    {
        return new TemplateEngine($this->template);
    }
} 