<?php

namespace App\Entity;

use App\Core\Services\UploadFileHelper;
use App\Entity\Compose\Metadata;
use App\Entity\Compose\MetadataInterface;
use App\Entity\Compose\Status;
use App\Entity\Compose\StatusInterface;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, MetadataInterface, StatusInterface
{
    use Metadata;
    use Status;

    public const IS_CREATED = 0;
    public const PLACE_IS_CREATED = 'created';

    public const IS_VERIFIED = 1;
    public const PLACE_IS_VERIFIED = 'verified';

    public const IS_ACTIVE = 2;
    public const PLACE_IS_ACTIVE = 'active';

    public const IS_DISABLED = 3;
    public const PLACE_IS_DISABLED = 'disabled';

    public const TRANS_VERIFICATION = 'get_verification';
    public const TRANS_HAS_PUBLICATION = 'has_made_publication';
    public const TRANS_DISABLE = 'has_been_disabled';

    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public const ROLES = [
        'ROLE_USER' => 'User',
        'ROLE_ADMIN' => 'Admin'
    ];

    public const OPTIN = 1;
    public const OPTOUT = 0;

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
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disabled = 0;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=155)
     */
    private $lastName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $optin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Folder::class, mappedBy="user", orphanRemoval=true)
     */
    private $folder;


    public function __construct()
    {
        $this->status = self::IS_CREATED;
        $this->roles[] = self::ROLE_USER;
        $this->optin = 0;
        $this->folder = new ArrayCollection();
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
        return (string) $this->email;
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
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function getDisabled(): ?bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): self
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = strtolower($firstName);

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = strtolower($lastName);

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getOptin(): ?bool
    {
        return $this->optin;
    }

    public function setOptin(bool $optin): self
    {
        $this->optin = $optin;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImagePath(): string
    {
        return UploadFileHelper::FOLDER_IMAGE.'/'.$this->getImage();
    }

    /**
     * @return Collection|Folder[]
     */
    public function getFolder(): Collection
    {
        return $this->folder;
    }

    public function addFolder(Folder $folder): self
    {
        if (!$this->folder->contains($folder)) {
            $this->folder[] = $folder;
            $folder->setUser($this);
        }

        return $this;
    }

    public function removeFolder(Folder $folder): self
    {
        if ($this->folder->contains($folder)) {
            $this->folder->removeElement($folder);
            // set the owning side to null (unless already changed)
            if ($folder->getUser() === $this) {
                $folder->setUser(null);
            }
        }

        return $this;
    }

}
