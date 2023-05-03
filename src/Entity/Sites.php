<?php

namespace App\Entity;

use App\Repository\SitesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SitesRepository::class)]
class Sites
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options:['unsigned'=>true])]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique:true)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'site', targetEntity: Sorties::class)]
    private Collection $sortie;

    #[ORM\OneToMany(mappedBy: 'site', targetEntity: Participants::class)]
    private Collection $participant;

    public function __construct()
    {
        $this->sortie = new ArrayCollection();
        $this->participant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Sorties>
     */
    public function getSortie(): Collection
    {
        return $this->sortie;
    }

    public function addSortie(Sorties $sortie): self
    {
        if (!$this->sortie->contains($sortie)) {
            $this->sortie->add($sortie);
            $sortie->setSite($this);
        }

        return $this;
    }

    public function removeSortie(Sorties $sortie): self
    {
        if ($this->sortie->removeElement($sortie)) {
            // set the owning side to null (unless already changed)
            if ($sortie->getSite() === $this) {
                $sortie->setSite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Participants>
     */
    public function getParticipant(): Collection
    {
        return $this->participant;
    }

    public function addParticipant(Participants $participant): self
    {
        if (!$this->participant->contains($participant)) {
            $this->participant->add($participant);
            $participant->setSite($this);
        }

        return $this;
    }

    public function removeParticipant(Participants $participant): self
    {
        if ($this->participant->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getSite() === $this) {
                $participant->setSite(null);
            }
        }

        return $this;
    }
}
