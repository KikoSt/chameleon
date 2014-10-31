<?php
/**
 * Created by IntelliJ IDEA.
 * User: thomas
 * Date: 01.10.14
 * Time: 14:12
 */

class CategorySubscription
{
    //unusual naming necessary to be conform with database
    private $idCategory;
    private $userStatus;

    public function getIdCategory()
    {
        return $this->idCategory;
    }

    public function setIdCategory($idCategory)
    {
        $this->idCategory = $idCategory;
    }

    public function getUserStatus()
    {
        return $this->userStatus;
    }

    public function setUserStatus($userStatus)
    {
        $this->userStatus = $userStatus;
    }


} 