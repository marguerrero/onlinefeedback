<?php

namespace Admin\UserManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Concessionaire
 */
class Concessionaire
{
    /**
     * @var integer
     */
    private $creatorId;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $description;

    /**
     * @var integer
     */
    private $idconcessionaire;


    /**
     * Set creatorId
     *
     * @param integer $creatorId
     * @return Concessionaire
     */
    public function setCreatorId($creatorId)
    {
        $this->creatorId = $creatorId;

        return $this;
    }

    /**
     * Get creatorId
     *
     * @return integer 
     */
    public function getCreatorId()
    {
        return $this->creatorId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Concessionaire
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Concessionaire
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
     * Get idconcessionaire
     *
     * @return integer 
     */
    public function getIdconcessionaire()
    {
        return $this->idconcessionaire;
    }
}
