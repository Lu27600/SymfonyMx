<?php

namespace Maxcraft\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Perms
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Maxcraft\DefaultBundle\Entity\PermsRepository")
 */
class Perms
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
     * @ORM\Column(name="groupName", type="string", length=255, unique=true)
     */
    private $groupName;

    /**
     * @var string
     *
     * @ORM\Column(name="perms", type="text")
     */
    private $perms;


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
     * Set groupName
     *
     * @param string $groupName
     *
     * @return Perms
     */
    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;

        return $this;
    }

    /**
     * Get groupName
     *
     * @return string
     */
    public function getGroupName()
    {
        return $this->groupName;
    }

    /**
     * Set perms
     *
     * @param string $perms
     *
     * @return Perms
     */
    public function setPerms($perms)
    {
        $this->perms = $perms;

        return $this;
    }

    /**
     * Get perms
     *
     * @return string
     */
    public function getPerms()
    {
        return $this->perms;
    }

    public function objectToString(Perms $perm){
        $id = 'id="'.$perm->getId().'",';
        $groupName = 'groupname="'.$perm->getGroupName().'",';
        $perms = 'perms="'.$perm->getPerms().'",';

        $str = '-perms:'.$id.$groupName.$perms;
        strval($str);
        if ($str[strlen($str)-1]==',') $str[strlen($str)-1]=null;
        return $str;
    }
}

