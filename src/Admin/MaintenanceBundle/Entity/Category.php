<?php

namespace Admin\MaintenanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category", indexes={@ORM\Index(name="IDX_64C19C161220EA6", columns={"creator_id"})})
 * @ORM\Entity(repositoryClass="Admin\MaintenanceBundle\Entity\CategoryRepository")
 */
class Category
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
     * @ORM\Column(name="category_name", type="string", length=150, nullable=false)
     */
    private $categoryName;

    /**
     * @var integer
     *
     * @ORM\Column(name="idconcessionaire", type="integer", nullable=true)
     */
    private $idconcessionaire;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="category_id_seq", allocationSize=1, initialValue=1)
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
     * @param \Feedback\SurveyFormBundle\Entity\UserAccount $creator
     * @return Category
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
}
