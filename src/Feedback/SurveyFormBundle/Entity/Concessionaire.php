<?php

namespace Feedback\SurveyFormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Concessionaire
 *
 * @ORM\Table(name="concessionaire", uniqueConstraints={@ORM\UniqueConstraint(name="concessionaire_description_key", columns={"description"})})
 * @ORM\Entity
 */
class Concessionaire
{
    /**
     * @var integer
     *
     * @ORM\Column(name="creator_id", type="integer", nullable=true)
     */
    private $creatorId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="idconcessionaire", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="concessionaire_idconcessionaire_seq", allocationSize=1, initialValue=1)
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
