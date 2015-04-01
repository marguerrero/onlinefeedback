<?php

namespace Admin\LogsBundle\Entity;

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
     * @ORM\Column(name="affected_id", type="integer", nullable=false)
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
     * @var integer
     *
     * @ORM\Column(name="idsys_user_action_logs", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sys_user_action_logs_idsys_user_action_logs_seq", allocationSize=1, initialValue=1)
     */
    private $idsysUserActionLogs;


}
