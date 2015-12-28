<?php

namespace Maxcraft\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Faction
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Faction
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
     * @ORM\Column(name="uuid", type="string", length=255, unique=true, nullable=false)
     */
    private $uuid;

    /**
     * @var string
     * @Assert\NotBlank(message = "Vous devez donner un nom à votre faction !")
     * @Assert\Length(min = 3, minMessage = "Le nom de votre faction doit contenir au minimum 3 caractères !")
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank(message = "Vous n'avez pas créé de tag pour votre faction !")
     * @Assert\Length(min = 2, max = 6, minMessage="Le tag doit contenir 2 caractères au minimum.", maxMessage = "Le tag doit contenir moins de 6 caractères.")
     * @ORM\Column(name="tag", type="string", length=255, unique=true)
     */
    private $tag;

    /**
     * @var string
     *
     * @ORM\Column(name="balance", type="decimal", precision=64, scale=2)
     */
    private $balance;

    /**
     * @var string
     *
     * @ORM\Column(name="spawn", type="string", length=255, nullable=true)
     */
    private $spawn;

    /**
     * @var string
     *
     * @ORM\Column(name="jail", type="string", length=255, nullable=true)
     */
    private $jail;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="Maxcraft\DefaultBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @var string
     *
     * @ORM\Column(name="heads", type="text", nullable=true)
     */
    private $heads;

    /**
     * @var string
     *
     * @ORM\Column(name="members", type="text", nullable=true)
     */
    private $members;

    /**
     * @var string
     *
     * @ORM\Column(name="recruits", type="text", nullable=true)
     */
    private $recruits;

    /**
     * @var string
     *
     * @ORM\Column(name="enemies", type="text", nullable=true)
     */
    private $enemies;

    /**
     * @var string
     *
     * @ORM\Column(name="allies", type="text", nullable=true)
     */
    private $allies;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=300, nullable=true)
     */
    private $icon;

    /**
     * @var string
     *
     * @ORM\Column(name="banner", type="text", nullable=true)
     */
    private $banner;


    public function __construct(){
        $this->balance = 0;
        $this->uuid = uniqid("fac",false);
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
     * Set name
     *
     * @param string $name
     *
     * @return Faction
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
     * Set tag
     *
     * @param string $tag
     *
     * @return Faction
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set balance
     *
     * @param string $balance
     *
     * @return Faction
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return string
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set spawn
     *
     * @param string $spawn
     *
     * @return Faction
     */
    public function setSpawn($spawn)
    {
        $this->spawn = $spawn;

        return $this;
    }

    /**
     * Get spawn
     *
     * @return string
     */
    public function getSpawn()
    {
        return $this->spawn;
    }

    /**
     * Set jail
     *
     * @param string $jail
     *
     * @return Faction
     */
    public function setJail($jail)
    {
        $this->jail = $jail;

        return $this;
    }

    /**
     * Get jail
     *
     * @return string
     */
    public function getJail()
    {
        return $this->jail;
    }

    /**
     * Set owner
     *
     * @param \Maxcraft\DefaultBundle\Entity\User $owner
     * @return Faction
     */
    public function setOwner(User $owner)
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
     * Set heads
     *
     * @param string $heads
     *
     * @return Faction
     */
    public function setHeads($heads)
    {
        $this->heads = $heads;

        return $this;
    }

    /**
     * Get heads
     *
     * @return string
     */
    public function getHeads()
    {
        return $this->heads;
    }

    /**
     * Set members
     *
     * @param string $members
     *
     * @return Faction
     */
    public function setMembers($members)
    {
        $this->members = $members;

        return $this;
    }

    /**
     * Get members
     *
     * @return string
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Set recruits
     *
     * @param string $recruits
     *
     * @return Faction
     */
    public function setRecruits($recruits)
    {
        $this->recruits = $recruits;

        return $this;
    }

    /**
     * Get recruits
     *
     * @return string
     */
    public function getRecruits()
    {
        return $this->recruits;
    }

    /**
     * Set enemies
     *
     * @param string $enemies
     *
     * @return Faction
     */
    public function setEnemies($enemies)
    {
        $this->enemies = $enemies;

        return $this;
    }

    /**
     * Get enemies
     *
     * @return string
     */
    public function getEnemies()
    {
        return $this->enemies;
    }

    /**
     * Set allies
     *
     * @param string $allies
     *
     * @return Faction
     */
    public function setAllies($allies)
    {
        $this->allies = $allies;

        return $this;
    }

    /**
     * Get allies
     *
     * @return string
     */
    public function getAllies()
    {
        return $this->allies;
    }

    /**
     * Set icon
     *
     * @param string $icon
     *
     * @return Faction
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set banner
     *
     * @param string $banner
     *
     * @return Faction
     */
    public function setBanner($banner)
    {
        $this->banner = $banner;

        return $this;
    }

    /**
     * Get banner
     *
     * @return string
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * @return mixed
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param mixed $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @param Faction $faction
     * @return string
     */
    public  function objectToString(Faction $faction){

        $id = "id=".'"'.$faction->getId().'",';
        $uuid = 'uuid="'.$faction->getUuid().'",';
        if($faction->getName()== null){$name = 'name="null",';} else{$name = "name=".'"'.$faction->getName().'",';}
        $tag="tag=".'"'.$faction->getTag().'",';
        $balance = 'balance="'.$faction->getBalance().'",';
        if ($faction->getSpawn()==null){$spawn = 'spawn="null",';} else{$spawn = 'spawn="'.$faction->getSpawn().'",';}
        if ($faction->getJail() == null){$jail = 'jail="null",';} else {$jail = 'jail:"'.$faction->getJail().'",';}
        $owner = "owner=".'"'.$faction->getOwner()->getUuid().'",';
        if ($faction->getHeads()==null){$heads = 'heads="null",';} else{$heads = "heads=".'"'.$faction->getHeads().'",';}
        if ($faction->getMembers()==null){$members = 'members="null",';} else{$members = "members=".'"'.$faction->getMembers().'",';}
        if ($faction->getRecruits()==null){$recruits = 'recruits="null",';} else{$recruits = "recruits=".'"'.$faction->getRecruits().'",';}
        if ($faction->getEnemies()==null){$enemies = 'enemies="null",';} else{$enemies = "enemies=".'"'.$faction->getEnemies().'",';}
        if ($faction->getAllies()==null){$allies = 'allies="null",';} else{$allies = "allies=".'"'.$faction->getAllies().'",';}
        if ($faction->getIcon()==null){$icon = 'icon="null",';} else{$icon = "icon=".'"'.$faction->getIcon().'",';}
        if ($faction->getBanner()==null){$banner = 'banner="null",';} else{$banner = "banner=".'"'.$faction->getBanner().'",';}

        $str = "-faction:".$id.$uuid.$name.$tag.$balance.$spawn.$jail.$owner.$heads.$members.$recruits.$enemies.$allies.$icon.$banner;
        strval($str);
        if ($str[strlen($str)-1]==',') $str[strlen($str)-1]=null;
        return $str;
    }
}
