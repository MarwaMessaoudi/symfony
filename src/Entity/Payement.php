<?php

namespace App\Entity;

use App\Repository\PayementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PayementRepository::class)
 */
class Payement
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
    private $NomPrenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numtel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $methodePayement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numCarte;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codeSecurite;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $prixTotal;

    /**
     * @ORM\OneToOne(targetEntity=ReservationFlight::class, inversedBy="payement", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $payementFlight;

    /**
     * @ORM\OneToOne(targetEntity=ReservationHotel::class, inversedBy="payement", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $payementHotel;

    /**
     * @ORM\OneToOne(targetEntity=ReservationTour::class, inversedBy="payement", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $peyementTour;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPrenom(): ?string
    {
        return $this->NomPrenom;
    }

    public function setNomPrenom(string $NomPrenom): self
    {
        $this->NomPrenom = $NomPrenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNumtel(): ?string
    {
        return $this->numtel;
    }

    public function setNumtel(string $numtel): self
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getMethodePayement(): ?string
    {
        return $this->methodePayement;
    }

    public function setMethodePayement(string $methodePayement): self
    {
        $this->methodePayement = $methodePayement;

        return $this;
    }

    public function getNumCarte(): ?string
    {
        return $this->numCarte;
    }

    public function setNumCarte(string $numCarte): self
    {
        $this->numCarte = $numCarte;

        return $this;
    }

    public function getCodeSecurite(): ?string
    {
        return $this->codeSecurite;
    }

    public function setCodeSecurite(string $codeSecurite): self
    {
        $this->codeSecurite = $codeSecurite;

        return $this;
    }

    public function getPrixTotal(): ?string
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(string $prixTotal): self
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    public function getPayementFlight(): ?ReservationFlight
    {
        return $this->payementFlight;
    }

    public function setPayementFlight(ReservationFlight $payementFlight): self
    {
        $this->payementFlight = $payementFlight;

        return $this;
    }

    public function getPayementHotel(): ?ReservationHotel
    {
        return $this->payementHotel;
    }

    public function setPayementHotel(ReservationHotel $payementHotel): self
    {
        $this->payementHotel = $payementHotel;

        return $this;
    }

    public function getPeyementTour(): ?ReservationTour
    {
        return $this->peyementTour;
    }

    public function setPeyementTour(ReservationTour $peyementTour): self
    {
        $this->peyementTour = $peyementTour;

        return $this;
    }
}
