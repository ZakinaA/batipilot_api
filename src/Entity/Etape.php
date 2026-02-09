<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EtapeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtapeRepository::class)]
#[ApiResource]
class Etape
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $archive = null;

    #[ORM\ManyToOne]
    private ?EtapeFormat $etapeFormat = null;

    #[ORM\ManyToOne(inversedBy: 'etapes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Poste $poste = null;

    /**
     * @var Collection<int, ChantierEtape>
     */
    #[ORM\OneToMany(targetEntity: ChantierEtape::class, mappedBy: 'etape')]
    private Collection $chantierEtapes;

    public function __construct()
    {
        $this->chantierEtapes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): static
    {
        $this->libelle = $libelle;

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

    public function getEtapeFormat(): ?EtapeFormat
    {
        return $this->etapeFormat;
    }

    public function setEtapeFormat(?EtapeFormat $etapeFormat): static
    {
        $this->etapeFormat = $etapeFormat;

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
            $chantierEtape->setEtape($this);
        }

        return $this;
    }

    public function removeChantierEtape(ChantierEtape $chantierEtape): static
    {
        if ($this->chantierEtapes->removeElement($chantierEtape)) {
            // set the owning side to null (unless already changed)
            if ($chantierEtape->getEtape() === $this) {
                $chantierEtape->setEtape(null);
            }
        }

        return $this;
    }
}
