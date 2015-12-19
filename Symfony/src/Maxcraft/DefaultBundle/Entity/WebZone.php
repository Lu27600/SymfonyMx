<?php

namespace Maxcraft\DefaultBundle\Entity;

use Doctrine\DBAL\Types\IntegerType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

/**
 * WebZone
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class WebZone
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
     * @ORM\Column(name="zoneId", type="integer", unique="true")
     */
    private $zoneId;

    /**
     * @var integer
     *
     * @ORM\Column(name="album", type="integer", nullable="true")
     */
    private $album;

    /**
     * @var boolean
     *
     * @ORM\Column(name="shopDemand", type="boolean")
     */
    private $shopDemand;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable="true")
     */
    private $description;

    public function __construct($zoneId){
        $this->setZoneId($zoneId);
        $this->setAlbum(null);
        $this->setShopDemand(false);
        $this->setDescription(null);
    }


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
     * @return WebZone
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
     * Set album
     *
     * @param integer $album
     *
     * @return WebZone
     */
    public function setAlbum($album)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Get album
     *
     * @return integer
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * Set shopDemand
     *
     * @param boolean $shopDemand
     *
     * @return WebZone
     */
    public function setShopDemand($shopDemand)
    {
        $this->shopDemand = $shopDemand;

        return $this;
    }

    /**
     * Get shopDemand
     *
     * @return boolean
     */
    public function getShopDemand()
    {
        return $this->shopDemand;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return WebZone
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
}

