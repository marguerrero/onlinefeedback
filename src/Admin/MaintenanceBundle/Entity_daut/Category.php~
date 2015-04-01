<?php

namespace Admin\MaintenanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


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
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="category_id_seq", allocationSize=1, initialValue=1)
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
     * @ORM\OneToMany(targetEntity="Admin\MaintenanceBundle\Entity\Questions", mappedBy="category")
     */
    private $questions;

    
    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

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
     * @return Category
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
    
}
