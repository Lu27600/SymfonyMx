<?php

namespace Maxcraft\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;


/**
 * Jobs
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Maxcraft\DefaultBundle\Entity\JobsRepository")
 */
class Jobs
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
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @ORM\Column(name="metier", type="string", length=255)
     */
    private $metier;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @ORM\Column(name="xp", type="decimal", precision=64, scale=11)
     */
    private $xp;


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
     * Set metier
     *
     * @param string $metier
     *
     * @return Jobs
     */
    public function setMetier($metier)
    {
        $this->metier = $metier;

        return $this;
    }

    /**
     * Get metier
     *
     * @return string
     */
    public function getMetier()
    {
        return $this->metier;
    }

    /**
     * Set xp
     *
     * @param string $xp
     *
     * @return Jobs
     */
    public function setXp($xp)
    {
        $this->xp = $xp;

        return $this;
    }

    /**
     * Get xp
     *
     * @return string
     */
    public function getXp()
    {
        return $this->xp;
    }
}

