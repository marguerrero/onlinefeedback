<?php

namespace Admin\MaintenanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Questions
 *
 * @ORM\Table(name="questions", indexes={@ORM\Index(name="IDX_8ADC54D591D79BD3", columns={"c_id"}), @ORM\Index(name="IDX_8ADC54D561220EA6", columns={"creator_id"})})
 * @ORM\Entity(repositoryClass="Admin\MaintenanceBundle\Entity\QuestionRepository")
 */
class Questions
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=150, nullable=false)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="questions_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Admin\MaintenanceBundle\Entity\UserAccount
     *
     * @ORM\ManyToOne(targetEntity="Admin\MaintenanceBundle\Entity\UserAccount")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     * })
     */
    private $creator;

    /**
     * @var \Admin\MaintenanceBundle\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="Admin\MaintenanceBundle\Entity\Category", inversedBy="questions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="c_id", referencedColumnName="id")
     * })
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
     * @param \Admin\MaintenanceBundle\Entity\UserAccount $creator
     * @return Questions
     */
    public function setCreator(\Admin\MaintenanceBundle\Entity\UserAccount $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \Admin\MaintenanceBundle\Entity\UserAccount 
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set c
     *
     * @param \Admin\MaintenanceBundle\Entity\Category $c
     * @return Questions
     */
    public function setC(\Admin\MaintenanceBundle\Entity\Category $c = null)
    {
        $this->c = $c;

        return $this;
    }

    /**
     * Get c
     *
     * @return \Admin\MaintenanceBundle\Entity\Category 
     */
    public function getC()
    {
        return $this->c;
    }
    
    
    /**
     * @var string
     */
    private $type;


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
}
