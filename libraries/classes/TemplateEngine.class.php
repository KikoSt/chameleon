<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 23.07.14
 * Time: 11:44
 */

class TemplateEngine
{
    protected $template;
    protected $variables = array();

    public function __construct($template)
    {
        $this->template = $template;
    }

    public function __get($key)
    {
        return $this->variables[$key];
    }

    public function __set($key, $value)
    {
        $this->variables[$key] = $value;
    }

    public function __toString()
    {
        extract($this->variables);

        chdir(dirname($this->template));
        ob_start();

        include basename($this->template);

        return ob_get_clean();
    }

    // TODO: hard coding for now, since general rework of the editor might make general changes necessary, so
    // avoiding to spend too much time on it for now
    public function getId()
    {
        return $this->variables['id'];
    }

    public function getWidth()
    {
        return $this->variables['width'];
    }

    public function getHeight()
    {
        return $this->variables['height'];
    }

//    public function getGroups()
//    {
//        return $this->variables['groups'];
//    }
}
