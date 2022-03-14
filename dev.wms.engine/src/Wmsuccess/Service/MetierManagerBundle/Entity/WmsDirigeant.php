<?php

namespace App\Wmsuccess\Service\MetierManagerBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
/**
 * WmsDirigeant
 *
 * @ORM\Table(name="wms_dirigeant")
 * @ORM\Entity
 */
class WmsDirigeant
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="dirigeant_last_name", type="string", length=100, nullable=false)
     */
    private $dirigeantLastName;

    /**
     * @var string
     *
     * @ORM\Column(name="dirigeant_first_name", type="string", length=100, nullable=false)
     */
    private $dirigeantFirstName;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string",length=100,nullable=false)
     */
    private $sexe;


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }


    /**
     * @return string
     */
    public function getDirigeantLastName(): string
    {
        return $this->dirigeantLastName;
    }

    /**
     * @param string $staffLastName
     */
    public function setDirigeantLastName(string $dirigeantLastName): void
    {
        $this->dirigeantLastName = $dirigeantLastName;
    }

    /**
     * @return string
     */
    public function getStaffFirstName(): string
    {
        return $this->dirigeantFirstName;
    }

    /**
     * @param string $dirigeantFirstName
     */
    public function setDirigeantFirstName(string $dirigeantFirstName): void
    {
        $this->dirigeantFirstName = $dirigeantFirstName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getSexe(): string
    {
        return $this->sexe;
    }

    /**
     * @param string $sexe
     */
    public function setSexe(string $sexe): void
    {
        $this->sexe = $sexe;
    }

}