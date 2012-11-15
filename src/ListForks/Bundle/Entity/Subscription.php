<?php

namespace ListForks\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ListForks\Bundle\Entity\Subscription
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Subscription
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="subscriptions")
     */
    private $user;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ForkList", inversedBy="subscriptions")
     */
    private $forklist;


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
     * Set user
     *
     * @param \ListForks\Bundle\Entity\User $user
     * @return Subscription
     */
    public function setUser(\ListForks\Bundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \ListForks\Bundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set forklist
     *
     * @param \ListForks\Bundle\Entity\ForkList $forklist
     * @return Subscription
     */
    public function setForklist(\ListForks\Bundle\Entity\ForkList $forklist = null)
    {
        $this->forklist = $forklist;
    
        return $this;
    }

    /**
     * Get forklist
     *
     * @return \ListForks\Bundle\Entity\ForkList 
     */
    public function getForklist()
    {
        return $this->forklist;
    }
}