<?php

namespace Maxcraft\DefaultBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Zone
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Maxcraft\DefaultBundle\Entity\ZoneRepository")
 */
class Zone
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
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var Zone
     *
     * @ORM\ManyToOne(targetEntity="Maxcraft\DefaultBundle\Entity\Zone")
     * @ORM\JoinColumn(nullable=true)
     */
    private $parent;

    /**
     * @var string
     * @ORM\Column(name="points", type="string", length=255)
     */
    private $points;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="Maxcraft\DefaultBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $owner;

    /**
     * @var World
     * @ORM\ManyToOne(targetEntity="Maxcraft\DefaultBundle\Entity\World")
     * @ORM\Column(nullable=false)
     */
    private $world;

    /**
     * @var string
     *
     * @ORM\Column(name="tags", type="string", length=300, nullable=true)
     */
    private $tags;

    /**
     * @var Builder
     *
     * @ORM\OneToMany(targetEntity="Maxcraft\DefaultBundle\Entity\Builder", mappedBy="zone", cascade={"persist"})
     */
    private $builders;

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
     *
     * @return Zone
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
     * Set points
     *
     * @param string $points
     *
     * @return Zone
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return string
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set tags
     *
     * @param string $tags
     *
     * @return Zone
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set parent
     *
     * @param \Maxcraft\DefaultBundle\Entity\Zone $parent
     *
     * @return Zone
     */
    public function setParent(Zone $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Maxcraft\DefaultBundle\Entity\Zone
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set owner
     *
     * @param \Maxcraft\DefaultBundle\Entity\User $owner
     *
     * @return Zone
     */
    public function setOwner(User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Maxcraft\DefaultBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->builders = new ArrayCollection();
    }

    /**
     * Add builder
     *
     * @param \Maxcraft\DefaultBundle\Entity\Builder $builder
     *
     * @return Zone
     */
    public function addBuilder(Builder $builder)
    {
        $this->builders[] = $builder;
        $builder->setZone($this);

        return $this;
    }

    /**
     * Remove builder
     *
     * @param \Maxcraft\DefaultBundle\Entity\Builder $builder
     * @return $this
     */
    public function removeBuilder(Builder $builder)
    {
        $this->builders->removeElement($builder);

    }

    /**
     * Get builders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBuilders()
    {
        return $this->builders;
    }

    /**
     * Set world
     *
     * @param string $world
     *
     * @return Zone
     */
    public function setWorld($world)
    {
        $this->world = $world;

        return $this;
    }

    /**
     * Get world
     *
     * @return string
     */
    public function getWorld()
    {
        return $this->world;
    }
}
