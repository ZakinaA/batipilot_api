<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ChantierPosteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChantierPosteRepository::class)]
#[ApiResource]
class ChantierPoste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'chantierPostes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Chantier $chantier = null;

    #[ORM\ManyToOne(inversedBy: 'chantierPostes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Poste $poste = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantHT = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantTTC = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantFournitures = null;

    #[ORM\Column(nullable: true)]
    private ?float $nbJoursTravailles = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantPrestataire = null;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $nomPrestataire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChantier(): ?Chantier
    {
        return $this->chantier;
    }

    public function setChantier(?Chantier $chantier): static
    {
        $this->chantier = $chantier;

        return $this;
    }

    public function getPoste(): ?Poste
    {
        return $this->poste;
    }

    public function setPoste(?Poste $poste): static
    {
        $this->poste = $poste;

        return $this;
    }

    public function getMontantHT(): ?float
    {
        return $this->montantHT;
    }

    public function setMontantHT(?float $montantHT): static
    {
        $this->montantHT = $montantHT;

        return $this;
    }

    public function getMontantTTC(): ?float
    {
        return $this->montantTTC;
    }

    public function setMontantTTC(?float $montantTTC): static
    {
        $this->montantTTC = $montantTTC;

        return $this;
    }

    public function getMontantFournitures(): ?float
    {
        return $this->montantFournitures;
    }

    public function setMontantFournitures(?float $montantFournitures): static
    {
        $this->montantFournitures = $montantFournitures;

        return $this;
    }

    public function getNbJoursTravailles(): ?float
    {
        return $this->nbJoursTravailles;
    }

    public function setNbJoursTravailles(?float $nbJoursTravailles): static
    {
        $this->nbJoursTravailles = $nbJoursTravailles;

        return $this;
    }

    public function getMontantPrestataire(): ?float
    {
        return $this->montantPrestataire;
    }

    public function setMontantPrestataire(?float $montantPrestataire): static
    {
        $this->montantPrestataire = $montantPrestataire;

        return $this;
    }

    public function getNomPrestataire(): ?string
    {
        return $this->nomPrestataire;
    }

    public function setNomPrestataire(?string $nomPrestataire): static
    {
        $this->nomPrestataire = $nomPrestataire;

        return $this;
    }
}
