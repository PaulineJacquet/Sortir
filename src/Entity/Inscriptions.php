<?php

namespace App\Entity;

use App\Repository\InscriptionsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InscriptionsRepository::class)]
class Inscriptions
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(options:['unsigned'=>true])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateInscription = null;

    #[ORM\ManyToOne(targetEntity: Participants::class, inversedBy: 'inscription')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Participants $participant = null;

    #[ORM\ManyToOne(targetEntity: Participants::class, inversedBy: 'inscription')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sorties $sortie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): self
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getParticipant(): ?Participants
    {
        return $this->participant;
    }

    public function setParticipant(?Participants $participant): self
    {
        $this->participant = $participant;

        return $this;
    }

    public function getSortie(): ?Sorties
    {
        return $this->sortie;
    }

    public function setSortie(?Sorties $sortie): self
    {
        $this->sortie = $sortie;

        return $this;
    }
}