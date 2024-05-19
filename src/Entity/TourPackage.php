<?php

namespace App\Entity;

use App\Repository\TourPackageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TourPackageRepository::class)
 */
class TourPackage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $prix;

    /**
     * @ORM\OneToMany(targetEntity=ReservationTour::class, mappedBy="tourPackage")
     */
    private $TourPackage;

    public function __construct()
    {
        $this->TourPackage = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $decription): self
    {
        $this->description = $decription;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, ReservationTour>
     */
    public function getTourPackage(): Collection
    {
        return $this->TourPackage;
    }

    public function addTourPackage(ReservationTour $tourPackage): self
    {
        if (!$this->TourPackage->contains($tourPackage)) {
            $this->TourPackage[] = $tourPackage;
            $tourPackage->setTourPackage($this);
        }

        return $this;
    }

    public function removeTourPackage(ReservationTour $tourPackage): self
    {
        if ($this->TourPackage->removeElement($tourPackage)) {
            // set the owning side to null (unless already changed)
            if ($tourPackage->getTourPackage() === $this) {
                $tourPackage->setTourPackage(null);
            }
        }

        return $this;
    }
}
