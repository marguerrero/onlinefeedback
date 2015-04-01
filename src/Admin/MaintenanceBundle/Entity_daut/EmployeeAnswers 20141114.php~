<?php

namespace Admin\MaintenanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmployeeAnswers
 *
 * @ORM\Table(name="employee_answers", indexes={@ORM\Index(name="IDX_D09C90096BC704C7", columns={"q_id"}), @ORM\Index(name="IDX_D09C900961220EA6", columns={"creator_id"})})
 * @ORM\Entity
 */
class EmployeeAnswers
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="actionstamp", type="datetime", nullable=false)
     */
    private $actionstamp;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=150, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=150, nullable=false)
     */
    private $value;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="employee_answers_id_seq", allocationSize=1, initialValue=1)
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
     * @var \Admin\MaintenanceBundle\Entity\Questions
     *
     * @ORM\ManyToOne(targetEntity="Admin\MaintenanceBundle\Entity\Questions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="q_id", referencedColumnName="id")
     * })
     */
    private $q;



    /**
     * Set actionstamp
     *
     * @param \DateTime $actionstamp
     * @return EmployeeAnswers
     */
    public function setActionstamp($actionstamp)
    {
        $this->actionstamp = $actionstamp;

        return $this;
    }

    /**
     * Get actionstamp
     *
     * @return \DateTime 
     */
    public function getActionstamp()
    {
        return $this->actionstamp;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return EmployeeAnswers
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return EmployeeAnswers
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
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
     * @return EmployeeAnswers
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
     * Set q
     *
     * @param \Admin\MaintenanceBundle\Entity\Questions $q
     * @return EmployeeAnswers
     */
    public function setQ(\Admin\MaintenanceBundle\Entity\Questions $q = null)
    {
        $this->q = $q;

        return $this;
    }

    /**
     * Get q
     *
     * @return \Admin\MaintenanceBundle\Entity\Questions 
     */
    public function getQ()
    {
        return $this->q;
    }
}
