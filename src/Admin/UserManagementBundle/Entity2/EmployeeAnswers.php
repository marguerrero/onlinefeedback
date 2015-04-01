<?php

namespace Admin\UserManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmployeeAnswers
 */
class EmployeeAnswers
{
    /**
     * @var \DateTime
     */
    private $actionstamp;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $value;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Admin\UserManagementBundle\Entity\Questions
     */
    private $q;

    /**
     * @var \Admin\UserManagementBundle\Entity\UserAccount
     */
    private $creator;


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
     * Set q
     *
     * @param \Admin\UserManagementBundle\Entity\Questions $q
     * @return EmployeeAnswers
     */
    public function setQ(\Admin\UserManagementBundle\Entity\Questions $q = null)
    {
        $this->q = $q;

        return $this;
    }

    /**
     * Get q
     *
     * @return \Admin\UserManagementBundle\Entity\Questions 
     */
    public function getQ()
    {
        return $this->q;
    }

    /**
     * Set creator
     *
     * @param \Admin\UserManagementBundle\Entity\UserAccount $creator
     * @return EmployeeAnswers
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
}
