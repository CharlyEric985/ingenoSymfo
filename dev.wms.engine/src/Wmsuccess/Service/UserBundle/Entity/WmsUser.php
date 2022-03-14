<?php

namespace App\Wmsuccess\Service\UserBundle\Entity;

use App\Wmsuccess\Service\MetierManagerBundle\Entity\WmsFile;
use App\Wmsuccess\Service\MetierManagerBundle\Entity\WmsRole;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @UniqueEntity("email")
 * @ORM\Entity
 */
class WmsUser implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="usr_firstname", type="string", length=200, nullable=true)
     */
    private $usrFirstname;

    /**
     * @ORM\Column(name="usr_lastname", type="string", length=200, nullable=true)
     */
    private $usrLastname;

    /**
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    private $enabled = false;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="usr_date_create", type="datetime", nullable=true)
     */
    private $usrDateCreate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="usr_date_update", type="datetime", nullable=true)
     */
    private $usrDateUpdate;

    /**
     * @var WmsFile
     *
     * @ORM\ManyToOne(targetEntity="App\Wmsuccess\Service\MetierManagerBundle\Entity\WmsFile")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wms_file_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    private $wmsFile;

    /**
     * @var WmsRole
     *
     * @ORM\ManyToOne(targetEntity="App\Wmsuccess\Service\MetierManagerBundle\Entity\WmsRole")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="wms_role_id", referencedColumnName="id")
     * })
     */
    private $wmsRole;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usrDateCreate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword($password)
    {
        if($password) $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return \DateTime
     */
    public function getUsrDateCreate(): \DateTime
    {
        return $this->usrDateCreate;
    }

    /**
     * @param \DateTime $usrDateCreate
     */
    public function setUsrDateCreate(\DateTime $usrDateCreate): void
    {
        $this->usrDateCreate = $usrDateCreate;
    }

    /**
     * @return \DateTime
     */
    public function getUsrDateUpdate(): \DateTime
    {
        return $this->usrDateUpdate;
    }

    /**
     * @param \DateTime $usrDateUpdate
     */
    public function setUsrDateUpdate(\DateTime $usrDateUpdate): void
    {
        $this->usrDateUpdate = $usrDateUpdate;
    }

    /**
     * @return mixed
     */
    public function getUsrFirstname()
    {
        return $this->usrFirstname;
    }

    /**
     * @param mixed $usrFirstname
     */
    public function setUsrFirstname($usrFirstname): void
    {
        $this->usrFirstname = $usrFirstname;
    }

    /**
     * @return mixed
     */
    public function getUsrLastname()
    {
        return $this->usrLastname;
    }

    /**
     * @param mixed $usrLastname
     */
    public function setUsrLastname($usrLastname): void
    {
        $this->usrLastname = $usrLastname;
    }

    /**
     * @return WmsFile
     */
    public function getWmsFile()
    {
        return $this->wmsFile;
    }

    /**
     * @param WmsFile $wmsFile
     */
    public function setWmsFile(WmsFile $wmsFile): void
    {
        $this->wmsFile = $wmsFile;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return WmsRole
     */
    public function getWmsRole()
    {
        return $this->wmsRole;
    }

    /**
     * @param WmsRole $wmsRole
     */
    public function setWmsRole(WmsRole $wmsRole)
    {
        $this->wmsRole = $wmsRole;
    }

}
