<?php

namespace Admin\EmailRecipientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Questions
 *
 * @ORM\Table(name="questions", indexes={@ORM\Index(name="IDX_8ADC54D591D79BD3", columns={"c_id"}), @ORM\Index(name="IDX_8ADC54D561220EA6", columns={"creator_id"})})
 * @ORM\Entity
 */
class Questions
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
     * @ORM\Column(name="description", type="string", length=150, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=150, nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="questions_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Admin\EmailRecipientBundle\Entity\UserAccount
     *
     * @ORM\ManyToOne(targetEntity="Admin\EmailRecipientBundle\Entity\UserAccount")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     * })
     */
    private $creator;

    /**
     * @var \Admin\EmailRecipientBundle\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="Admin\EmailRecipientBundle\Entity\Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="c_id", referencedColumnName="id")
     * })
     */
    private $c;


}
