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
     * @var string $location
     *
     * @ORM\Column(name="location", type="string")
     */
    private $location;

    /**
     * @var integer $rating
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;

    /**
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="forklist")
     */
    private $items;

    /**
     *
     * @ORM\OneToMany(targetEntity="Subscription", mappedBy="forklist")
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
     * Set location
     *
     * @param string $location
     * @return List
     */
    public function setLocation($location)
    {
        $this->location = $location;
    
        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
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
     * Add items
     *
     * @param ListForks\Bundle\Entity\Item $items
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
     * @param ListForks\Bundle\Entity\Item $items
     */
    public function removeItem(\ListForks\Bundle\Entity\Item $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add subscriptions
     *
     * @param ListForks\Bundle\Entity\Subscription $subscriptions
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
     * @param ListForks\Bundle\Entity\Subscription $subscriptions
     */
    public function removeSubscription(\ListForks\Bundle\Entity\Subscription $subscriptions)
    {
        $this->subscriptions->removeElement($subscriptions);
    }

    /**
     * Get subscriptions
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }
}