<?php

namespace Feedback\SurveyFormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SysUserActionLogs
 *
 * @ORM\Table(name="sys_user_action_logs")
 * @ORM\Entity
 */
class SysUserActionLogs
{
    /**
     * @var string
     *
     * @ORM\Column(name="module", type="string", length=80, nullable=true)
     */
    private $module;

    /**
     * @var integer
     *
     * @ORM\Column(name="affected_id", type="integer", nullable=true)
     */
    private $affectedId;

    /**
     * @var string
     *
     * @ORM\Column(name="affected_data", type="text", nullable=false)
     */
    private $affectedData;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="actionstamp", type="datetime", nullable=true)
     */
    private $actionstamp;

    /**
     * @var string
     *
     * @ORM\Column(name="method", type="string", length=100, nullable=true)
     */
    private $method;

    /**
     * @var integer
     *
     * @ORM\Column(name="idsys_user_action_logs", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sys_user_action_logs_idsys_user_action_logs_seq", allocationSize=1, initialValue=1)
     */
    private $idsysUserActionLogs;



    /**
     * Set module
     *
     * @param string $module
     * @return SysUserActionLogs
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module
     *
     * @return string 
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set affectedId
     *
     * @param integer $affectedId
     * @return SysUserActionLogs
     */
    public function setAffectedId($affectedId)
    {
        $this->affectedId = $affectedId;

        return $this;
    }

    /**
     * Get affectedId
     *
     * @return integer 
     */
    public function getAffectedId()
    {
        return $this->affectedId;
    }

    /**
     * Set affectedData
     *
     * @param string $affectedData
     * @return SysUserActionLogs
     */
    public function setAffectedData($affectedData)
    {
        $this->affectedData = $affectedData;

        return $this;
    }

    /**
     * Get affectedData
     *
     * @return string 
     */
    public function getAffectedData()
    {
        return $this->affectedData;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return SysUserActionLogs
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
     * @return SysUserActionLogs
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
     * Set method
     *
     * @param string $method
     * @return SysUserActionLogs
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method
     *
     * @return string 
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get idsysUserActionLogs
     *
     * @return integer 
     */
    public function getIdsysUserActionLogs()
    {
        return $this->idsysUserActionLogs;
    }
}
