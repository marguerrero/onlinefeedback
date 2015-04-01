<?php

namespace Admin\UserManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SysUserActionLogs
 */
class SysUserActionLogs
{
    /**
     * @var string
     */
    private $module;

    /**
     * @var integer
     */
    private $affectedId;

    /**
     * @var string
     */
    private $affectedData;

    /**
     * @var string
     */
    private $username;

    /**
     * @var \DateTime
     */
    private $actionstamp;

    /**
     * @var integer
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
     * Get idsysUserActionLogs
     *
     * @return integer 
     */
    public function getIdsysUserActionLogs()
    {
        return $this->idsysUserActionLogs;
    }
}
