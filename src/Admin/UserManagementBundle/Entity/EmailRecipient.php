<?php

namespace Admin\UserManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmailRecipient
 *
 * @ORM\Table(name="email_recipient", uniqueConstraints={@ORM\UniqueConstraint(name="email_recipients_decription_key", columns={"email"})})
 * @ORM\Entity
 */
class EmailRecipient
{
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="email_recipient_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;


}
