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
     * @var string
     *
     * @ORM\Column(name="grouping", type="string", length=150, nullable=false)
     */
    private $grouping;

    /**
     * @var string
     *
     * @ORM\Column(name="optional", type="string", length=150, nullable=false)
     */
    private $optional;
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=150, nullable=false)
     */
    private $type;

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
     * @var \Feedback\SurveyFormBundle\Entity\UserAccount
     *
     * @ORM\ManyToOne(targetEntity="Feedback\SurveyFormBundle\Entity\UserAccount")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     * })
     */
    private $creator;

    /**
     * @var \Feedback\SurveyFormBundle\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="Feedback\SurveyFormBundle\Entity\Category")
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
     * Set grouping
     *
     * @param string $grouping
     * @return Questions
     */
    public function setGrouping($grouping)
    {
        $this->grouping = $grouping;

        return $this;
    }

    /**
     * Get grouping
     *
     * @return string 
     */
    public function getGrouping()
    {
        return $this->grouping;
    }


    /**
     * Set optional
     *
     * @param string $optional
     * @return Questions
     */
    public function setOptional($optional)
    {
        $this->optional = $optional;

        return $this;
    }

    /**
     * Get optional
     *
     * @return string 
     */
    public function getOptional()
    {
        return $this->optional;
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
     * @param \Feedback\SurveyFormBundle\Entity\UserAccount $creator
     * @return Questions
     */
    public function setCreator(\Feedback\SurveyFormBundle\Entity\UserAccount $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \Feedback\SurveyFormBundle\Entity\UserAccount 
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set c
     *
     * @param \Feedback\SurveyFormBundle\Entity\Category $c
     * @return Questions
     */
    public function setC($c = null)
    {
        $this->c = $c;

        return $this;
    }

    /**
     * Get c
     *
     * @return \Feedback\SurveyFormBundle\Entity\Category 
     */
    public function getC()
    {
        return $this->c;
    }
}
