<?php

namespace Maxcraft\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

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
     * @var integer
     *
     * @ORM\Column(name="parent", type="integer", nullable=true)
     */
    private $parent;

    /**
     * @var string
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @ORM\Column(name="points", type="string", length=255)
     */
    private $points;

    /**
     * @var string
     *
     * @ORM\Column(name="owner", type="string", length=255, nullable=true)
     */
    private $owner;

    /**
     * @var string
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @ORM\Column(name="world", type="string", length=255)
     */
    private $world;

    /**
     * @var string
     *
     * @ORM\Column(name="tags", type="string", length=300, nullable=true)
     */
    private $tags;

    /**
     * @var string
     *
     * @ORM\Column(name="builders", type="text", nullable=true)
     */
    private $builders;

    /**
     * @var string
     *
     * @ORM\Column(name="cuboiders", type="text", nullable=true)
     */
    private $cuboiders;




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
     * Set parent
     *
     * @param integer $parent
     *
     * @return Zone
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return integer
     */
    public function getParent()
    {
        return $this->parent;
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
     * Set owner
     *
     * @param string $owner
     *
     * @return Zone
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
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
     * Set builders
     *
     * @param string $builders
     *
     * @return Zone
     */
    public function setBuilders($builders)
    {
        $this->builders = $builders;

        return $this;
    }

    /**
     * Get builders
     *
     * @return string
     */
    public function getBuilders()
    {
        return $this->builders;
    }

    /**
     * Set cuboiders
     *
     * @param string $cuboiders
     *
     * @return Zone
     */
    public function setCuboiders($cuboiders)
    {
        $this->cuboiders = $cuboiders;

        return $this;
    }

    /**
     * Get cuboiders
     *
     * @return string
     */
    public function getCuboiders()
    {
        return $this->cuboiders;
    }

    /**
     * @param Zone $zone
     * @return string
     */
    public  function objectToString(Zone $zone){

        $id = "id=".'"'.$zone->getId().'",';
        if($zone->getName()== null){$name = 'name="null",';} else{$name = "name=".'"'.$zone->getName().'",';}
        if($zone->getParent()==null){$parent = 'parent="null",';} else{$parent = "parent=".'"'.$zone->getParent().'",';}
        $points = "points=".'"'.$zone->getPoints().'",';
        if ($zone->getOwner()==null){$owner = 'owner="null",';} else{$owner = "owner=".'"'.$zone->getOwner().'",';}
        $world = 'world="'.$zone->getWorld().'",';
        if ($zone->getTags()==null) { $tags='tags="null",';} else{$tags="tags=".'"'.$zone->getTags().'",';}
        if ($zone->getBuilders()==null) { $builders='builders="null",';} else{$builders="builders=".'"'.$zone->getBuilders().'",';}
        if ($zone->getCuboiders()==null) {$cuboiders='cuboiders="null",';} else{$cuboiders="cuboiders=".'"'.$zone->getCuboiders().'"';}

        $str = "-zone:".$id.$name.$parent.$points.$owner.$world.$tags.$builders.$cuboiders;
        strval($str);
        if ($str[strlen($str)-1]==',') $str[strlen($str)-1]=null;
        return $str;
    }

}

