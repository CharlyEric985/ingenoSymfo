<?php

namespace App\Wmsuccess\Service\MetierManagerBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
/**
 * WmsVille
 *
 * @ORM\Table(name="wms_ville")
 * @ORM\Entity
 */
class WmsVille
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
     * @ORM\ManyToOne(targetEntity="WmsCode", inversedBy="WmsVille")
     * @ORM\JoinColumn(name="code_id", referencedColumnName="id")
     */
    private $wmsCode;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_ville", type="string", length=50, nullable=false)
     */
    private $nom_ville;

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
    public function getNomVille(): string
    {
        return $this->nom_ville;
    }

    /**
     * @param string $nom_ville
     */
    public function setNomVille(string $nom_ville): void
    {
        $this->nom_ville = $nom_ville;
    }

    /**
     * @return mixed
     */
    public function getWmsCode()
    {
        return $this->wmsCode;
    }

    /**
     * @param mixed $wmsCode
     */
    public function setWmsCode($wmsCode): void
    {
        $this->wmsCode = $wmsCode;
    }

}