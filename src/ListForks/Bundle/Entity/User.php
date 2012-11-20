<?php

namespace ListForks\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class User
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
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=20)
     */
    private $first_name;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=20)
     */
    private $last_name;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string")
     */
    private $location;

    /**
     *
     * @ORM\OneToOne(targetEntity="Account")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $account;

    /**
     *
     * @ORM\OneToMany(targetEntity="ForkList", mappedBy="user", cascade={"persist", "remove"})
     */
    private $forklists;

    /**
     *
     * @ORM\OneToMany(targetEntity="Subscription", mappedBy="user", cascade={"persist", "remove"})
     */
    private $subscriptions;

    /**
     *
     * @ORM\OneToMany(targetEntity="Preference", mappedBy="user", cascade={"persist", "remove"})
     */
    private $preferences;


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
     * Set first_name
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;
    
        return $this;
    }

    /**
     * Get first_name
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set last_name
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;
    
        return $this;
    }

    /**
     * Get last_name
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return User
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
     * Constructor
     */
    public function __construct()
    {
        $this->first_name = '';
        $this->last_name = '';
        $this->location = '';

        $this->forklists = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subscriptions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->preferences = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set account
     *
     * @param \ListForks\Bundle\Entity\Account $account
     * @return User
     */
    public function setAccount(\ListForks\Bundle\Entity\Account $account = null)
    {
        $this->account = $account;
    
        return $this;
    }

    /**
     * Get account
     *
     * @return \ListForks\Bundle\Entity\Account 
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Add forklists
     *
     * @param \ListForks\Bundle\Entity\ForkList $forklists
     * @return User
     */
    public function addForklist(\ListForks\Bundle\Entity\ForkList $forklists)
    {
        $this->forklists[] = $forklists;
    
        return $this;
    }

    /**
     * Remove forklists
     *
     * @param \ListForks\Bundle\Entity\ForkList $forklists
     */
    public function removeForklist(\ListForks\Bundle\Entity\ForkList $forklists)
    {
        $this->forklists->removeElement($forklists);
    }

    /**
     * Get forklists
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getForklists()
    {
        return $this->forklists;
    }

    /**
     * Add subscriptions
     *
     * @param \ListForks\Bundle\Entity\Subscription $subscriptions
     * @return User
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

    /**
     * Add preferences
     *
     * @param \ListForks\Bundle\Entity\Preference $preferences
     * @return User
     */
    public function addPreference(\ListForks\Bundle\Entity\Preference $preferences)
    {
        $this->preferences[] = $preferences;
    
        return $this;
    }

    /**
     * Remove preferences
     *
     * @param \ListForks\Bundle\Entity\Preference $preferences
     */
    public function removePreference(\ListForks\Bundle\Entity\Preference $preferences)
    {
        $this->preferences->removeElement($preferences);
    }

    /**
     * Get preferences
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPreferences()
    {
        return $this->preferences;
    }
}