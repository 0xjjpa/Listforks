<?php

namespace ListForks\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Location
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Location
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
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     */
    private $longitude;

    /**
     *
     * @ORM\OneToOne(targetEntity="ForkList", inversedBy="location")
     * @ORM\JoinColumn(name="forklist_id", referencedColumnName="id")
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
     * Set latitude
     *
     * @param float $latitude
     * @return Location
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    
        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return Location
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    
        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set forklist
     *
     * @param \ListForks\Bundle\Entity\ForkList $forklist
     * @return Location
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