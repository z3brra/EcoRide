<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Identity
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 36, unique: true)]
    private ?string $uuid = null;


    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 64)]
    private ?string $pseudo = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;



    /*** Account / Security ***/
    #[ORM\Column(nullable: true)]
    private ?int $credits = null;

    #[ORM\Column]
    private ?bool $isBanned = null;

    #[ORM\Column(length: 255)]
    private ?string $apiToken = null;


    /*** Date / Time ***/
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    /*** Relations ***/

    /**
     * @var Collection<int, Vehicle>
     */
    #[ORM\OneToMany(targetEntity: Vehicle::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $vehicles;

    #[ORM\OneToOne(mappedBy: 'owner', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private ?FixedDriverPreference $fixedDriverPreference = null;

    /**
     * @var Collection<int, CustomDriverPreference>
     */
    #[ORM\OneToMany(targetEntity: CustomDriverPreference::class, mappedBy: 'owner', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $customDriverPreferences;

    /**
     * @var Collection<int, Drive>
     */
    #[ORM\OneToMany(targetEntity: Drive::class, mappedBy: 'owner')]
    private Collection $drives;

    /**
     * @var Collection<int, Drive>
     */
    #[ORM\ManyToMany(targetEntity: Drive::class, mappedBy: 'participants')]
    private Collection $joinedDrives;



    /** @throws Exception */
    public function __construct()
    {
        $this->uuid = Uuid::uuid7()->toString();
        $this->apiToken = bin2hex(random_bytes(20));
        $this->createdAt = new DateTimeImmutable();
        $this->vehicles = new ArrayCollection();
        $this->customDriverPreferences = new ArrayCollection();
        $this->drives = new ArrayCollection();
        $this->joinedDrives = new ArrayCollection();
    }

    /*** Anonymisation ***/
    public function anonymize(): void
    {
        if ($this->deletedAt !== null) {
            return;
        }
        $suffix = '-'.Uuid::uuid7()->toString();
        $this->pseudo = 'deleted';
        $this->email = 'deleted@exemple.local';
        $this->roles = ['ROLE_DELETED'];
        $this->deletedAt = new DateTimeImmutable();

        foreach ($this->vehicles as $vehicle) {
            $vehicle->anonymize();
        }
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    #[ORM\PreUpdate]
    public function onUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
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

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCredits(): ?int
    {
        return $this->credits;
    }

    public function setCredits(?int $credits): static
    {
        $this->credits = $credits;

        return $this;
    }

    public function isBanned(): ?bool
    {
        return $this->isBanned;
    }

    public function setIsBanned(bool $isBanned): static
    {
        $this->isBanned = $isBanned;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(string $apiToken): static
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /**
     * @return Collection<int, Vehicle>
     */
    public function getVehicles(): Collection
    {
        return $this->vehicles;
    }

    public function addVehicle(Vehicle $vehicle): static
    {
        if (!$this->vehicles->contains($vehicle)) {
            $this->vehicles->add($vehicle);
            $vehicle->setOwner($this);
        }

        return $this;
    }

    public function removeVehicle(Vehicle $vehicle): static
    {
        if ($this->vehicles->removeElement($vehicle)) {
            // set the owning side to null (unless already changed)
            if ($vehicle->getOwner() === $this) {
                $vehicle->setOwner(null);
            }
        }

        return $this;
    }

    public function getFixedDriverPreference(): ?FixedDriverPreference
    {
        return $this->fixedDriverPreference;
    }

    public function setFixedDriverPreference(FixedDriverPreference $fixedDriverPreference): static
    {
        // set the owning side of the relation if necessary
        if ($fixedDriverPreference->getOwner() !== $this) {
            $fixedDriverPreference->setOwner($this);
        }

        $this->fixedDriverPreference = $fixedDriverPreference;

        return $this;
    }

    /**
     * @return Collection<int, CustomDriverPreference>
     */
    public function getCustomDriverPreferences(): Collection
    {
        return $this->customDriverPreferences;
    }

    public function addCustomDriverPreference(CustomDriverPreference $customDriverPreference): static
    {
        if (!$this->customDriverPreferences->contains($customDriverPreference)) {
            $this->customDriverPreferences->add($customDriverPreference);
            $customDriverPreference->setOwner($this);
        }

        return $this;
    }

    public function removeCustomDriverPreference(CustomDriverPreference $customDriverPreference): static
    {
        if ($this->customDriverPreferences->removeElement($customDriverPreference)) {
            // set the owning side to null (unless already changed)
            if ($customDriverPreference->getOwner() === $this) {
                $customDriverPreference->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Drive>
     */
    public function getDrives(): Collection
    {
        return $this->drives;
    }

    public function addDrive(Drive $drive): static
    {
        if (!$this->drives->contains($drive)) {
            $this->drives->add($drive);
            $drive->setOwner($this);
        }

        return $this;
    }

    public function removeDrive(Drive $drive): static
    {
        if ($this->drives->removeElement($drive)) {
            // set the owning side to null (unless already changed)
            if ($drive->getOwner() === $this) {
                $drive->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Drive>
     */
    public function getJoinedDrives(): Collection
    {
        return $this->joinedDrives;
    }

    public function addJoinedDrive(Drive $joinedDrive): static
    {
        if (!$this->joinedDrives->contains($joinedDrive)) {
            $this->joinedDrives->add($joinedDrive);
            $joinedDrive->addParticipant($this);
        }

        return $this;
    }

    public function removeJoinedDrive(Drive $joinedDrive): static
    {
        if ($this->joinedDrives->removeElement($joinedDrive)) {
            $joinedDrive->removeParticipant($this);
        }

        return $this;
    }
}
