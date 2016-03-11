<?php

namespace Maxcraft\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FactionRole
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FactionRole
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
     * @var Faction
     *
     * @ORM\ManyToOne(targetEntity="Maxcraft\DefaultBundle\Entity\Faction")
     * @ORM\JoinColumn(name="faction", nullable=false)
     */
    private $faction;

    /**
     * @var Faction
     *
     * @ORM\ManyToOne(targetEntity="Maxcraft\DefaultBundle\Entity\Faction")
     * @ORM\JoinColumn(name="tothisfaction", nullable=false)
     */
    private $toThisFaction;

    /**
     * @var string
     *
     * @ORM\Column(name="hasRole", type="string", length=255)
     */
    private $hasRole;

    /**
     * @var datetime
     * @ORM\Column(name="since", type="datetime", nullable=false)
     */
    private $since;

    public function __controller(){
        $this->since = new \DateTime();
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
     * Set faction
     *
     * @param integer $faction
     *
     * @return FactionRole
     */
    public function setFaction($faction)
    {
        $this->faction = $faction;

        return $this;
    }

    /**
     * Get faction
     *
     * @return integer
     */
    public function getFaction()
    {
        return $this->faction;
    }

    /**
     * Set toThisFaction
     *
     * @param integer $toThisFaction
     *
     * @return FactionRole
     */
    public function setToThisFaction($toThisFaction)
    {
        $this->toThisFaction = $toThisFaction;

        return $this;
    }

    /**
     * Get toThisFaction
     *
     * @return integer
     */
    public function getToThisFaction()
    {
        return $this->toThisFaction;
    }

    /**
     * Set hasRole
     *
     * @param string $hasRole
     *
     * @return FactionRole
     */
    public function setHasRole($hasRole)
    {
        $this->hasRole = $hasRole;

        return $this;
    }

    /**
     * Get hasRole
     *
     * @return string
     */
    public function getHasRole()
    {
        return $this->hasRole;
    }

    /**
     * Set since
     *
     * @param \DateTime $since
     *
     * @return FactionRole
     */
    public function setSince($since)
    {
        $this->since = $since;

        return $this;
    }

    /**
     * Get since
     *
     * @return \DateTime
     */
    public function getSince()
    {
        return $this->since;
    }
}
