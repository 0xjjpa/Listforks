<?php

namespace ListForks\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ListForks\Bundle\Entity\Item
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Item
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
     * @var string $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var boolean $complete
     *
     * @ORM\Column(name="complete", type="boolean")
     */
    private $complete;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ForkList", inversedBy="items")
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
     * Set description
     *
     * @param string $description
     * @return Item
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
     * Set complete
     *
     * @param boolean $complete
     * @return Item
     */
    public function setComplete($complete)
    {
        $this->complete = $complete;
    
        return $this;
    }

    /**
     * Get complete
     *
     * @return boolean 
     */
    public function getComplete()
    {
        return $this->complete;
    }

    /**
     * Set forklist
     *
     * @param ListForks\Bundle\Entity\ForkList $forklist
     * @return Item
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
}