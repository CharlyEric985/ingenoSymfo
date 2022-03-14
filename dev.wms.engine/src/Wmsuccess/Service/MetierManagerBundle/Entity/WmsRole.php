<?php

namespace App\Wmsuccess\Service\MetierManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class WmsRole
 * @package App\Gs\Service\MetierManagerBundle\Entity
 *
 * @ORM\Entity(repositoryClass="App\Wmsuccess\Service\MetierManagerBundle\Repository\WmsRoleRepository")
 * @ORM\Table(name="wms_role")
 */
class WmsRole
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="rl_name", type="string", length=45, nullable=true)
     */
    private $rlName;

    /**
     * @var string
     *
     * @ORM\Column(name="rl_description", type="string", length=100, nullable=true)
     */
    private $rlDescription;

    /**
     * @return string
     */
    public function getRlDescription()
    {
        return $this->rlDescription;
    }

    /**
     * @param string $rlDescription
     */
    public function setRlDescription($rlDescription)
    {
        $this->rlDescription = $rlDescription;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRlName()
    {
        return $this->rlName;
    }

    /**
     * @param string $rlName
     */
    public function setRlName($rlName)
    {
        $this->rlName = $rlName;
    }
}
