<?php

namespace Admin\LoginBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmployeeAnswers
 *
 * @ORM\Table(name="employee_answers", indexes={@ORM\Index(name="IDX_D09C900961220EA6", columns={"creator_id"}), @ORM\Index(name="IDX_D09C90096BC704C7", columns={"q_id"})})
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
     * @var \Admin\LoginBundle\Entity\Questions
     *
     * @ORM\ManyToOne(targetEntity="Admin\LoginBundle\Entity\Questions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="q_id", referencedColumnName="id")
     * })
     */
    private $q;

    /**
     * @var \Admin\LoginBundle\Entity\UserAccount
     *
     * @ORM\ManyToOne(targetEntity="Admin\LoginBundle\Entity\UserAccount")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     * })
     */
    private $creator;


}
