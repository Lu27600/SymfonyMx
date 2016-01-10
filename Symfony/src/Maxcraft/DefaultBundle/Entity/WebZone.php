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
     * @var Zone
     * @ORM\OneToOne(targetEntity="Maxcraft\DefaultBundle\Entity\Zone")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $servZone;

    /**
     * @var Album
     *
     * @ORM\ManyToOne(targetEntity="Maxcraft\DefaultBundle\Entity\Album")
     * @ORM\JoinColumn(nullable=true)
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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @param Zone $servZone
     */
    public function __construct(Zone $servZone){
        $this->setServZone($servZone);
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

    /**
     * Set servZone
     *
     * @param \Maxcraft\DefaultBundle\Entity\Zone $servZone
     *
     * @return WebZone
     */
    public function setServZone(Zone $servZone)
    {
        $this->servZone = $servZone;

        return $this;
    }

    /**
     * Get servZone
     *
     * @return \Maxcraft\DefaultBundle\Entity\Zone
     */
    public function getServZone()
    {
        return $this->servZone;
    }

    /**
     * Set album
     *
     * @param \Maxcraft\DefaultBundle\Entity\Album $album
     *
     * @return WebZone
     */
    public function setAlbum(Album $album = null)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Get album
     *
     * @return \Maxcraft\DefaultBundle\Entity\Album
     */
    public function getAlbum()
    {
        return $this->album;
    }
}
