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
    private $layout;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param $layout
     * @return $this
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function getView()
    {
        return new TemplateEngine($this->layout);
    }

    public function create() {}

    public function loadPartial($pathToPartial, Array $params)
    {
        $partial = file_get_contents($pathToPartial);

        return $partial;
    }
} 