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
     * @ORM\ManyToOne(targetEntity="ForkList", inversedBy="subscriptions")
     */
    private $forklist;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="subscriptions")
     */
    private $profile;


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
     * Set forklist
     *
     * @param ListForks\Bundle\Entity\ForkList $forklist
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
     * @return ListForks\Bundle\Entity\ForkList 
     */
    public function getForklist()
    {
        return $this->forklist;
    }

    /**
     * Set profile
     *
     * @param ListForks\Bundle\Entity\Profile $profile
     * @return Subscription
     */
    public function setProfile(\ListForks\Bundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;
    
        return $this;
    }

    /**
     * Get profile
     *
     * @return ListForks\Bundle\Entity\Profile 
     */
    public function getProfile()
    {
        return $this->profile;
    }
}