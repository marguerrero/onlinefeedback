<?php

namespace Admin\MaintenanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmployeeLogs
 *
 * @ORM\Table(name="employee_logs")
 * @ORM\Entity
 */
class EmployeeLogs
{
    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=150, nullable=false)
     */
    private $username;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="actionstamp", type="datetime", nullable=false)
     */
    private $actionstamp;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="employee_logs_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;



    /**
     * Set username
     *
     * @param string $username
     * @return EmployeeLogs
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
     * Set actionstamp
     *
     * @param \DateTime $actionstamp
     * @return EmployeeLogs
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
