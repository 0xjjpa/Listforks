<?php

namespace ListForks\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rating
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Rating
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating", type="smallint")
     */
    private $rating;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ForkList", inversedBy="ratings")
     * @ORM\JoinColumn(name="forklist_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $forklist;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="ratings")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;


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
     * Set rating
     *
     * @param integer $rating
     * @return Rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    
        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set forklist
     *
     * @param \ListForks\Bundle\Entity\ForkList $forklist
     * @return Rating
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

    /**
     * Set user
     *
     * @param \ListForks\Bundle\Entity\User $user
     * @return Rating
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
}