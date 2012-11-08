<?php

namespace ListForks\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ListForks\Bundle\Entity\Preference
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Preference
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
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var boolean $flag
     *
     * @ORM\Column(name="flag", type="boolean")
     */
    private $flag;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="preferences")
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
     * Set name
     *
     * @param string $name
     * @return Preference
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
     * @return Preference
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
     * Set flag
     *
     * @param boolean $flag
     * @return Preference
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    
        return $this;
    }

    /**
     * Get flag
     *
     * @return boolean 
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Set profile
     *
     * @param ListForks\Bundle\Entity\Profile $profile
     * @return Preference
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