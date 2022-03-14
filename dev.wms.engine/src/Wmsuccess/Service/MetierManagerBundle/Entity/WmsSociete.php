<?php

namespace App\Wmsuccess\Service\MetierManagerBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
/**
 * WmsSociete
 *
 * @ORM\Table(name="wms_societe")
 * @ORM\Entity
 */
class WmsSociete
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
     * @ORM\Column(name="societe_name", type="string", length=50, nullable=false)
     */
    private $societeName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=500, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="types", type="string",length=50,nullable=false)
     */
    private $types;

    /**
     * @var string
     *
     * @ORM\Column(name="code_postal", type="string",length=10,nullable=false)
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string",length=50,nullable=false)
     */
    private $ville;

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
    public function getSocieteName(): string
    {
        return $this->societeName;
    }

    /**
     * @param string $societeName
     */
    public function setSocieteName(string $societeName): void
    {
        $this->societeName = $societeName;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getTypes(): string
    {
        return $this->types;
    }

    /**
     * @param string $types
     */
    public function setTypes(string $types): void
    {
        $this->types = $types;
    }

    /**
     * @return string
     */
    public function getCodePostal(): string
    {
        return $this->codePostal;
    }

    /**
     * @param string $codePostal
     */
    public function setCodePostal(string $codePostal): void
    {
        $this->codePostal = $codePostal;
    }

    /**
     * @return string
     */
    public function getVille(): string
    {
        return $this->ville;
    }

    /**
     * @param string $ville
     */
    public function setVille(string $ville): void
    {
        $this->ville = $ville;
    }

}