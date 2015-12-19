<?php

namespace Maxcraft\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Moderation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Maxcraft\DefaultBundle\Entity\ModerationRepository")
 */
class Moderation
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
     * @ORM\Column(name="uuid", type="string", length=255)
     */
    private $uuid;

    /**
     * @var boolean
     * @ORM\Column(name="ismute", type="boolean")
     */
    private $ismute;

    /**
     * @var integer
     * @ORM\Column(name="muteend", type="bigint")
     */
    private $muteend;

    /**
     * @var boolean
     * @ORM\Column(name="isjail", type="boolean")
     */
    private $isjail;

    /**
     * @var integer
     * @ORM\Column(name="jailend", type="bigint")
     */
    private $jailend;

    /**
     * @var boolean
     * @ORM\Column(name="isban", type="boolean")
     */
    private $isban;

    /**
     * @var integer
     * @ORM\Column(name="banend", type="bigint")
     */
    private $banend;

    public function __construct(){
        $this->ismute = false;
        $this->isjail = false;
        $this->isban = false;
        $this->muteend = -1;
        $this->jailend = -1;
        $this->banend = -1;
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
     * Set ismute
     *
     * @param boolean $ismute
     *
     * @return Moderation
     */
    public function setIsmute($ismute)
    {
        $this->ismute = $ismute;

        return $this;
    }

    /**
     * Get ismute
     *
     * @return boolean
     */
    public function getIsmute()
    {
        return $this->ismute;
    }

    /**
     * Set muteend
     *
     * @param integer $muteend
     *
     * @return Moderation
     */
    public function setMuteend($muteend)
    {
        $this->muteend = $muteend;

        return $this;
    }

    /**
     * Get muteend
     *
     * @return integer
     */
    public function getMuteend()
    {
        return $this->muteend;
    }

    /**
     * Set isjail
     *
     * @param boolean $isjail
     *
     * @return Moderation
     */
    public function setIsjail($isjail)
    {
        $this->isjail = $isjail;

        return $this;
    }

    /**
     * Get isjail
     *
     * @return boolean
     */
    public function getIsjail()
    {
        return $this->isjail;
    }

    /**
     * Set jailend
     *
     * @param integer $jailend
     *
     * @return Moderation
     */
    public function setJailend($jailend)
    {
        $this->jailend = $jailend;

        return $this;
    }

    /**
     * Get jailend
     *
     * @return integer
     */
    public function getJailend()
    {
        return $this->jailend;
    }

    /**
     * Set isban
     *
     * @param boolean $isban
     *
     * @return Moderation
     */
    public function setIsban($isban)
    {
        $this->isban = $isban;

        return $this;
    }

    /**
     * Get isban
     *
     * @return boolean
     */
    public function getIsban()
    {
        return $this->isban;
    }

    /**
     * Set banend
     *
     * @param integer $banend
     *
     * @return Moderation
     */
    public function setBanend($banend)
    {
        $this->banend = $banend;

        return $this;
    }

    /**
     * Get banend
     *
     * @return integer
     */
    public function getBanend()
    {
        return $this->banend;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @param Moderation $moderation
     * @return string
     */
    public function objectToString(Moderation $moderation){
        $id = 'id="'.$moderation->getId().'",';
        $uuid = 'uuid="'.$moderation->getUuid().'",';
        $ismute = 'ismute="'.$moderation->getIsmute().'",';
        $muteend = 'muteend="'.$moderation->getMuteend().'",';
        $isjail = 'isjail="'.$moderation->getIsjail().'",';
        $jailend  ='jailend="'.$moderation->getJailend().'",';
        $isban = 'isban="'.$moderation->getIsban().'",';
        $banend = 'banend="'.$moderation->getBanend().'",';

        $str = '-moderation:'.$id.$uuid.$ismute.$muteend.$isjail.$jailend.$isban.$banend;
        strval($str);
        if ($str[strlen($str)-1]==',') $str[strlen($str)-1]=null;
        return $str;
    }
}
