<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PosteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PosteRepository::class)]
#[ApiResource]
class Poste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $libelle = null;

    #[ORM\Column(nullable: true)]
    private ?float $tva = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $equipe = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $prestataire = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $ordre = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $archive = null;

    /**
     * @var Collection<int, Etape>
     */
    #[ORM\OneToMany(targetEntity: Etape::class, mappedBy: 'poste')]
    private Collection $etapes;

    /**
     * @var Collection<int, ChantierPoste>
     */
    #[ORM\OneToMany(targetEntity: ChantierPoste::class, mappedBy: 'poste')]
    private Collection $chantierPostes;

    public function __construct()
    {
        $this->etapes = new ArrayCollection();
        $this->chantierPostes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(?float $tva): static
    {
        $this->tva = $tva;

        return $this;
    }

    public function getEquipe(): ?int
    {
        return $this->equipe;
    }

    public function setEquipe(?int $equipe): static
    {
        $this->equipe = $equipe;

        return $this;
    }

    public function getPrestataire(): ?int
    {
        return $this->prestataire;
    }

    public function setPrestataire(?int $prestataire): static
    {
        $this->prestataire = $prestataire;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): static
    {
        $this->ordre = $ordre;

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

    /**
     * @return Collection<int, Etape>
     */
    public function getEtapes(): Collection
    {
        return $this->etapes;
    }

    public function addEtape(Etape $etape): static
    {
        if (!$this->etapes->contains($etape)) {
            $this->etapes->add($etape);
            $etape->setPoste($this);
        }

        return $this;
    }

    public function removeEtape(Etape $etape): static
    {
        if ($this->etapes->removeElement($etape)) {
            // set the owning side to null (unless already changed)
            if ($etape->getPoste() === $this) {
                $etape->setPoste(null);
            }
        }

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
            $chantierPoste->setPoste($this);
        }

        return $this;
    }

    public function removeChantierPoste(ChantierPoste $chantierPoste): static
    {
        if ($this->chantierPostes->removeElement($chantierPoste)) {
            // set the owning side to null (unless already changed)
            if ($chantierPoste->getPoste() === $this) {
                $chantierPoste->setPoste(null);
            }
        }

        return $this;
    }
}
