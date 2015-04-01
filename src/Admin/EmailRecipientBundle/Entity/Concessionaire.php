<?php

namespace Admin\EmailRecipientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Concessionaire
 *
 * @ORM\Table(name="concessionaire", uniqueConstraints={@ORM\UniqueConstraint(name="concessionaire_description_key", columns={"description"})})
 * @ORM\Entity
 */
class Concessionaire
{
    /**
     * @var integer
     *
     * @ORM\Column(name="creator_id", type="integer", nullable=true)
     */
    private $creatorId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="idconcessionaire", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="concessionaire_idconcessionaire_seq", allocationSize=1, initialValue=1)
     */
    private $idconcessionaire;


}
