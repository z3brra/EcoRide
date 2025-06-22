<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 36, unique: true)]
    private ?string $uuid = null;

    #[ORM\Column(length: 20, unique: true)]
    private ?string $licensePlate = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $firstLicenseDate = null;

    #[ORM\Column]
    private ?bool $isElectric = null;

    #[ORM\Column(length: 20)]
    private ?string $color = null;

    #[ORM\Column]
    private ?int $seats = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    /**
     * @var Collection<int, Drive>
     */
    #[ORM\OneToMany(targetEntity: Drive::class, mappedBy: 'vehicle')]
    private Collection $drives;


    public function __construct()
    {
        $this->uuid = Uuid::uuid7()->toString();
        $this->drives = new ArrayCollection();
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

    public function getLicensePlate(): ?string
    {
        return $this->licensePlate;
    }

    public function setLicensePlate(string $licensePlate): static
    {
        $this->licensePlate = $licensePlate;

        return $this;
    }

    public function getFirstLicenseDate(): ?\DateTimeImmutable
    {
        return $this->firstLicenseDate;
    }

    public function setFirstLicenseDate(\DateTimeImmutable $firstLicenseDate): static
    {
        $this->firstLicenseDate = $firstLicenseDate;

        return $this;
    }

    public function isElectric(): ?bool
    {
        return $this->isElectric;
    }

    public function setIsElectric(bool $isElectric): static
    {
        $this->isElectric = $isElectric;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getSeats(): ?int
    {
        return $this->seats;
    }

    public function setSeats(int $seats): static
    {
        $this->seats = $seats;

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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

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
            $drive->setVehicle($this);
        }

        return $this;
    }

    public function removeDrive(Drive $drive): static
    {
        if ($this->drives->removeElement($drive)) {
            // set the owning side to null (unless already changed)
            if ($drive->getVehicle() === $this) {
                $drive->setVehicle(null);
            }
        }

        return $this;
    }

    

}
