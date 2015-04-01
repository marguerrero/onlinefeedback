<?php

namespace Admin\UserManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Questions
 */
class Questions
{
    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $type;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Admin\UserManagementBundle\Entity\UserAccount
     */
    private $creator;

    /**
     * @var \Admin\UserManagementBundle\Entity\Category
     */
    private $c;


    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Questions
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
     * Set description
     *
     * @param string $description
     * @return Questions
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Questions
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
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
     * @return Questions
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

    /**
     * Set c
     *
     * @param \Admin\UserManagementBundle\Entity\Category $c
     * @return Questions
     */
    public function setC(\Admin\UserManagementBundle\Entity\Category $c = null)
    {
        $this->c = $c;

        return $this;
    }

    /**
     * Get c
     *
     * @return \Admin\UserManagementBundle\Entity\Category 
     */
    public function getC()
    {
        return $this->c;
    }
}
