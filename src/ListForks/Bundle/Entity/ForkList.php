<?php

namespace ListForks\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ListForks\Bundle\Entity\ForkList
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ForkList
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
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var boolean $private
     *
     * @ORM\Column(name="private", type="boolean")
     */
    private $private;

    /**
     * @var integer $rating
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;

    /**
     *
     * @ORM\OneToOne(targetEntity="Location", mappedBy="forklist", cascade={"persist", "remove"})
     */
    private $location;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="forklists")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="forklist", cascade={"persist", "remove"})
     * @ORM\OrderBy({"orderNumber" = "ASC"})
     */
    private $items;

    /**
     *
     * @ORM\OneToMany(targetEntity="Subscription", mappedBy="forklist", cascade={"persist", "remove"})
     */
    private $subscriptions;


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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ForkList
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return ForkList
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return List
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return List
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set private
     *
     * @param boolean $private
     * @return List
     */
    public function setPrivate($private)
    {
        $this->private = $private;
    
        return $this;
    }

    /**
     * Get private
     *
     * @return boolean 
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * Get private (string)
     *
     * @return string
     */
    public function getPrivateToString($private)
    {
        return ($private ? 'true' : 'false');
    }

    /**
     * Set rating
     *
     * @param integer $rating
     * @return List
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
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subscriptions = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set location
     *
     * @param \ListForks\Bundle\Entity\Location $location
     * @return ForkList
     */
    public function setLocation(\ListForks\Bundle\Entity\Location $location = null)
    {
        $this->location = $location;
    
        return $this;
    }

    /**
     * Get location
     *
     * @return \ListForks\Bundle\Entity\Location 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set user
     *
     * @param \ListForks\Bundle\Entity\User $user
     * @return ForkList
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
     * Add items
     *
     * @param \ListForks\Bundle\Entity\Item $items
     * @return ForkList
     */
    public function addItem(\ListForks\Bundle\Entity\Item $items)
    {
        $this->items[] = $items;
    
        return $this;
    }

    /**
     * Remove items
     *
     * @param \ListForks\Bundle\Entity\Item $items
     */
    public function removeItem(\ListForks\Bundle\Entity\Item $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add subscriptions
     *
     * @param \ListForks\Bundle\Entity\Subscription $subscriptions
     * @return ForkList
     */
    public function addSubscription(\ListForks\Bundle\Entity\Subscription $subscriptions)
    {
        $this->subscriptions[] = $subscriptions;
    
        return $this;
    }

    /**
     * Remove subscriptions
     *
     * @param \ListForks\Bundle\Entity\Subscription $subscriptions
     */
    public function removeSubscription(\ListForks\Bundle\Entity\Subscription $subscriptions)
    {
        $this->subscriptions->removeElement($subscriptions);
    }

    /**
     * Get subscriptions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

}