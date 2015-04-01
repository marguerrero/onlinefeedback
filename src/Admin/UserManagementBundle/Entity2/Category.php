<?php

namespace Admin\UserManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 */
class Category
{
    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var string
     */
    private $categoryName;

    /**
     * @var integer
     */
    private $idconcessionaire;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Admin\UserManagementBundle\Entity\UserAccount
     */
    private $creator;


    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Category
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set categoryName
     *
     * @param string $categoryName
     * @return Category
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    /**
     * Get categoryName
     *
     * @return string 
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * Set idconcessionaire
     *
     * @param integer $idconcessionaire
     * @return Category
     */
    public function setIdconcessionaire($idconcessionaire)
    {
        $this->idconcessionaire = $idconcessionaire;

        return $this;
    }

    /**
     * Get idconcessionaire
     *
     * @return integer 
     */
    public function getIdconcessionaire()
    {
        return $this->idconcessionaire;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set creator
     *
     * @param \Admin\UserManagementBundle\Entity\UserAccount $creator
     * @return Category
     */
    public function setCreator(\Admin\UserManagementBundle\Entity\UserAccount $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \Admin\UserManagementBundle\Entity\UserAccount 
     */
    public function getCreator()
    {
        return $this->creator;
    }
}
