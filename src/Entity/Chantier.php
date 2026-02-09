<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ChantierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChantierRepository::class)]
#[ApiResource]
class Chantier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $copos = null;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateDebutPrevue = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateDemarrage = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateReception = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateFin = null;

    #[ORM\Column(nullable: true)]
    private ?float $surfacePlancher = null;

    #[ORM\Column(nullable: true)]
    private ?float $surfaceHabitable = null;

    #[ORM\Column(nullable: true)]
    private ?int $distanceDepot = null;

    #[ORM\Column(nullable: true)]
    private ?int $tempsTrajet = null;

    #[ORM\Column(nullable: true)]
    private ?float $coefficient = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alerte = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $archive = null;

    #[ORM\ManyToOne(inversedBy: 'chantiers')]
    private ?Equipe $equipe = null;

    /**
     * @var Collection<int, ChantierPoste>
     */
    #[ORM\OneToMany(targetEntity: ChantierPoste::class, mappedBy: 'chantier')]
    private Collection $chantierPostes;

    /**
     * @var Collection<int, ChantierEtape>
     */
    #[ORM\OneToMany(targetEntity: ChantierEtape::class, mappedBy: 'chantier')]
    private Collection $chantierEtapes;

    public function __construct()
    {
        $this->chantierPostes = new ArrayCollection();
        $this->chantierEtapes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCopos(): ?string
    {
        return $this->copos;
    }

    public function setCopos(?string $copos): static
    {
        $this->copos = $copos;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getDateDebutPrevue(): ?\DateTime
    {
        return $this->dateDebutPrevue;
    }

    public function setDateDebutPrevue(?\DateTime $dateDebutPrevue): static
    {
        $this->dateDebutPrevue = $dateDebutPrevue;

        return $this;
    }

    public function getDateDemarrage(): ?\DateTime
    {
        return $this->dateDemarrage;
    }

    public function setDateDemarrage(?\DateTime $dateDemarrage): static
    {
        $this->dateDemarrage = $dateDemarrage;

        return $this;
    }

    public function getDateReception(): ?\DateTime
    {
        return $this->dateReception;
    }

    public function setDateReception(?\DateTime $dateReception): static
    {
        $this->dateReception = $dateReception;

        return $this;
    }

    public function getDateFin(): ?\DateTime
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTime $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getSurfacePlancher(): ?float
    {
        return $this->surfacePlancher;
    }

    public function setSurfacePlancher(?float $surfacePlancher): static
    {
        $this->surfacePlancher = $surfacePlancher;

        return $this;
    }

    public function getSurfaceHabitable(): ?float
    {
        return $this->surfaceHabitable;
    }

    public function setSurfaceHabitable(?float $surfaceHabitable): static
    {
        $this->surfaceHabitable = $surfaceHabitable;

        return $this;
    }

    public function getDistanceDepot(): ?int
    {
        return $this->distanceDepot;
    }

    public function setDistanceDepot(?int $distanceDepot): static
    {
        $this->distanceDepot = $distanceDepot;

        return $this;
    }

    public function getTempsTrajet(): ?int
    {
        return $this->tempsTrajet;
    }

    public function setTempsTrajet(?int $tempsTrajet): static
    {
        $this->tempsTrajet = $tempsTrajet;

        return $this;
    }

    public function getCoefficient(): ?float
    {
        return $this->coefficient;
    }

    public function setCoefficient(?float $coefficient): static
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    public function getAlerte(): ?string
    {
        return $this->alerte;
    }

    public function setAlerte(?string $alerte): static
    {
        $this->alerte = $alerte;

        return $this;
    }

    public function getArchive(): ?int
    {
        return $this->archive;
    }

    public function setArchive(int $archive): static
    {
        $this->archive = $archive;

        return $this;
    }

    public function getEquipe(): ?Equipe
    {
        return $this->equipe;
    }

    public function setEquipe(?Equipe $equipe): static
    {
        $this->equipe = $equipe;

        return $this;
    }

    /**
     * @return Collection<int, ChantierPoste>
     */
    public function getChantierPostes(): Collection
    {
        return $this->chantierPostes;
    }

    public function addChantierPoste(ChantierPoste $chantierPoste): static
    {
        if (!$this->chantierPostes->contains($chantierPoste)) {
            $this->chantierPostes->add($chantierPoste);
            $chantierPoste->setChantier($this);
        }

        return $this;
    }

    public function removeChantierPoste(ChantierPoste $chantierPoste): static
    {
        if ($this->chantierPostes->removeElement($chantierPoste)) {
            // set the owning side to null (unless already changed)
            if ($chantierPoste->getChantier() === $this) {
                $chantierPoste->setChantier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ChantierEtape>
     */
    public function getChantierEtapes(): Collection
    {
        return $this->chantierEtapes;
    }

    public function addChantierEtape(ChantierEtape $chantierEtape): static
    {
        if (!$this->chantierEtapes->contains($chantierEtape)) {
            $this->chantierEtapes->add($chantierEtape);
            $chantierEtape->setChantier($this);
        }

        return $this;
    }

    public function removeChantierEtape(ChantierEtape $chantierEtape): static
    {
        if ($this->chantierEtapes->removeElement($chantierEtape)) {
            // set the owning side to null (unless already changed)
            if ($chantierEtape->getChantier() === $this) {
                $chantierEtape->setChantier(null);
            }
        }

        return $this;
    }
}
