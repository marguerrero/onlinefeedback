<?php

namespace Feedback\SurveyFormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Options
 *
 * @ORM\Table(name="options")
 * @ORM\Entity(repositoryClass="Feedback\SurveyFormBundle\Entity\OptionsRepository")
 */
class Options
{
    /**
     * @var integer
     *
     * @ORM\Column(name="q_id", type="integer", nullable=true)
     */
    private $qId;

    /**
     * @var string
     *
     * @ORM\Column(name="option_desc", type="string", length=150, nullable=true)
     */
    private $optionDesc;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="options_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;



    /**
     * Set qId
     *
     * @param integer $qId
     * @return Options
     */
    public function setQId($qId)
    {
        $this->qId = $qId;

        return $this;
    }

    /**
     * Get qId
     *
     * @return integer 
     */
    public function getQId()
    {
        return $this->qId;
    }

    /**
     * Set optionDesc
     *
     * @param string $optionDesc
     * @return Options
     */
    public function setOptionDesc($optionDesc)
    {
        $this->optionDesc = $optionDesc;

        return $this;
    }

    /**
     * Get optionDesc
     *
     * @return string 
     */
    public function getOptionDesc()
    {
        return $this->optionDesc;
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
}
