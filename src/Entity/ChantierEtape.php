<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ChantierEtapeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChantierEtapeRepository::class)]
#[ApiResource]
class ChantierEtape
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'chantierEtapes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Chantier $chantier = null;

    #[ORM\ManyToOne(inversedBy: 'chantierEtapes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etape $etape = null;

    #[ORM\Column(nullable: true)]
    private ?bool $valBoolean = null;

    #[ORM\Column(nullable: true)]
    private ?int $valInteger = null;

    #[ORM\Column(nullable: true)]
    private ?float $valFloat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $valText = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $valDate = null;

    /*#[ORM\Column(nullable: true)]
    private ?\DateTime $valDateHeure = null;*/
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $valDateHeure = null;

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

    public function getEtape(): ?Etape
    {
        return $this->etape;
    }

    public function setEtape(?Etape $etape): static
    {
        $this->etape = $etape;

        return $this;
    }

    public function isValBoolean(): ?bool
    {
        return $this->valBoolean;
    }

    public function setValBoolean(?bool $valBoolean): static
    {
        $this->valBoolean = $valBoolean;

        return $this;
    }

    public function getValInteger(): ?int
    {
        return $this->valInteger;
    }

    public function setValInteger(?int $valInteger): static
    {
        $this->valInteger = $valInteger;

        return $this;
    }

    public function getValFloat(): ?float
    {
        return $this->valFloat;
    }

    public function setValFloat(?float $valFloat): static
    {
        $this->valFloat = $valFloat;

        return $this;
    }

    public function getValText(): ?string
    {
        return $this->valText;
    }

    public function setValText(?string $valText): static
    {
        $this->valText = $valText;

        return $this;
    }

    public function getValDate(): ?\DateTime
    {
        return $this->valDate;
    }

    public function setValDate(?\DateTime $valDate): static
    {
        $this->valDate = $valDate;

        return $this;
    }

    public function getValDateHeure(): ?\DateTime
    {
        return $this->valDateHeure;
    }

    public function setValDateHeure(?\DateTime $valDateHeure): static
    {
        $this->valDateHeure = $valDateHeure;

        return $this;
    }
}
