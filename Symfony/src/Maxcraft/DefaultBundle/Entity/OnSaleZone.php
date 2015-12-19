<?php

namespace Maxcraft\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * OnSaleZone
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Maxcraft\DefaultBundle\Entity\OnSaleZoneRepository")
 */
class OnSaleZone
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
     * @ORM\Column(name="zone_id", type="integer", unique=true)
     */
    private $zoneId;

    /**
     * @var string
     * @ORM\Column(name="price", type="decimal", precision=64, scale=2)
     */
    private $price;

    /**
     * @var boolean
     * @ORM\Column(name="forRent", type="boolean")
     */
    private $forRent;

    /**
     * @var string
     * 
     * @ORM\Column(name="location", type="string", length=50, nullable=true)
     */
    private $location;


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
     * Set zoneId
     *
     * @param integer $zoneId
     *
     * @return OnSaleZone
     */
    public function setZoneId($zoneId)
    {
        $this->zoneId = $zoneId;

        return $this;
    }

    /**
     * Get zoneId
     *
     * @return integer
     */
    public function getZoneId()
    {
        return $this->zoneId;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return OnSaleZone
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set forRent
     *
     * @param boolean $forRent
     *
     * @return OnSaleZone
     */
    public function setForRent($forRent)
    {
        $this->forRent = $forRent;

        return $this;
    }

    /**
     * Get forRent
     *
     * @return boolean
     */
    public function getForRent()
    {
        return $this->forRent;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return OnSaleZone
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

    public function objectToString(OnSaleZone $saleZone){
        $id = 'id="'.$saleZone->getId().'",';
        $zoneId = 'zoneid="'.$saleZone->getZoneId().'",';
        $price = 'price="'.$saleZone->getPrice().'",';
        $forrent = 'forrent="'.$saleZone->getForRent().'",';
        if ($saleZone->getLocation()==null){$location = 'location="null",';}else{$location = 'location="'.$saleZone->getLocation().'",';}

        $str = '-onsalezone:'.$id.$zoneId.$price.$forrent.$location;
        strval($str);
        if ($str[strlen($str)-1]==',') $str[strlen($str)-1]=null;
        return $str;
    }
}

