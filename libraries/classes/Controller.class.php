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
    private $user;
    private $company;
    private $advertiser;
    private $category;

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

    /**
     * @return mixed
     */
    public function getAdvertiser()
    {
        return $this->advertiser;
    }

    /**
     * @param mixed $advertiser
     */
    public function setAdvertiser($advertiser)
    {
        $this->advertiser = $advertiser;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
} 