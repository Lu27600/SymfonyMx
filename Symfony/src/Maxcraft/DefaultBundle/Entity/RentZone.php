<?php

namespace Maxcraft\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * RentZone
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class RentZone
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
     * @ORM\OneToOne(targetEntity="Maxcraft\DefaultBundle\Entity\Zone", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $zone;

    /**
     * @var string
     * @ORM\Column(name="tenant", type="string", length=255)
     */
    private $tenant;

    /**
     * @var string
     * @ORM\Column(name="price", type="decimal", precision=64, scale=2)
     */
    private $price;

    /**
     * @var string
     * @ORM\Column(name="lastpay", type="string", length=255, nullable=true)
     */
    private $lastpay;

    /**
     * @var string
     * @ORM\Column(name="location", type="string", length=50)
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
     * Set tenant
     *
     * @param string $tenant
     *
     * @return RentZone
     */
    public function setTenant($tenant)
    {
        $this->tenant = $tenant;

        return $this;
    }

    /**
     * Get tenant
     *
     * @return string
     */
    public function getTenant()
    {
        return $this->tenant;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return RentZone
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
     * Set lastpay
     *
     * @param string $lastpay
     *
     * @return RentZone
     */
    public function setLastpay($lastpay)
    {
        $this->lastpay = $lastpay;

        return $this;
    }

    /**
     * Get lastpay
     *
     * @return string
     */
    public function getLastpay()
    {
        return $this->lastpay;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return RentZone
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

    public function objectToString(RentZone $rentZone){
        $id = 'id="'.$rentZone->getId();
        $zoneId = 'zoneid="'.$rentZone->getZone()->getId().'",';
        $tenant = 'tenant="'.$rentZone->getTenant().'",';
        $price = 'price="'.$rentZone->getPrice().'",';
        if($rentZone->getLastpay()==null){$lastPay = 'lastpay="null",';} else{$lastPay = 'lastpay="'.$rentZone->getLastpay().'",';}
        $location = 'location="'.$rentZone->getLocation().'",';

        $str = '-rentzone:'.$id.$zoneId.$tenant.$price.$lastPay.$location;
        strval($str);
        if ($str[strlen($str)-1]==',') $str[strlen($str)-1]=null;
        return $str;
    }

    /**
     * Set zone
     *
     * @param \Maxcraft\DefaultBundle\Entity\Zone $zone
     *
     * @return RentZone
     */
    public function setZone(Zone $zone)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone
     *
     * @return \Maxcraft\DefaultBundle\Entity\Zone
     */
    public function getZone()
    {
        return $this->zone;
    }
}
