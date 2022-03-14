<?php

namespace App\Wmsuccess\Service\MetierManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WmsFile
 *
 * @ORM\Table(name="wms_file")
 * @ORM\Entity
 */
class WmsFile
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
     * @ORM\Column(name="fl_extension", type="string", length=10)
     */
    private $flExtension;

    /**
     * @var string
     *
     * @ORM\Column(name="fl_name", type="string", length=255)
     */
    private $flName;

    /**
     * @var string
     *
     * @ORM\Column(name="fl_url", type="string", length=255)
     */
    private $flUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="fl_nature", type="string", length=50, nullable=true)
     */
    private $flNature;

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
     * @return string
     */
    public function getFlExtension()
    {
        return $this->flExtension;
    }

    /**
     * @param string $flExtension
     */
    public function setFlExtension(string $flExtension)
    {
        $this->flExtension = $flExtension;
    }

    /**
     * @return string
     */
    public function getFlName()
    {
        return $this->flName;
    }

    /**
     * @param string $flName
     */
    public function setFlName(string $flName)
    {
        $this->flName = strlen($flName) > 200 ? substr($flName, 0, 200) : $flName;
    }

    /**
     * @return string
     */
    public function getFlUrl()
    {
        return $this->flUrl;
    }

    /**
     * @param string $flUrl
     */
    public function setFlUrl(string $flUrl)
    {
        $this->flUrl = $flUrl;
    }

    /**
     * @return string
     */
    public function getFlNature()
    {
        return $this->flNature;
    }

    /**
     * @param string $flNature
     */
    public function setFlNature(string $flNature)
    {
        $this->flNature = $flNature;
    }

}
