<?php

namespace App\Entity;

use App\Repository\SortiesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SortiesRepository::class)]
class Sorties
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(options:['unsigned'=>true])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[Assert\GreaterThan('now')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateHeureDebut = null;

    #[ORM\Column(nullable: true)]
    private ?int $duree = null;

    #[Assert\LessThan(propertyPath: 'dateHeureDebut')]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateLimiteInscription = null;

    #[ORM\Column]
    private ?int $nbInscriptionMax = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $infosSortie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motifAnnulation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoSortie = null;

    #[ORM\ManyToOne(targetEntity: Participants::class, fetch: 'EAGER', inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Participants $organisateur = null;

    #[ORM\ManyToOne(targetEntity:Lieu::class,inversedBy: 'sortie')]
    private ?Lieu $lieu = null;

    #[ORM\ManyToOne(targetEntity: Etats::class, cascade: ["persist"], inversedBy: 'sorties')]
    private ?Etats $etat = null;

    #[ORM\ManyToOne(targetEntity: Sites::class, inversedBy: 'sortie')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sites $site = null;

    #[ORM\ManyToMany(targetEntity: Participants::class, mappedBy: 'estInscrit')]
    private Collection $participe;

    public function __construct()
    {
        $this->inscription = new ArrayCollection();
        $this->participe = new ArrayCollection();
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

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getNbInscriptionMax(): ?int
    {
        return $this->nbInscriptionMax;
    }

    public function setNbInscriptionMax(int $nbInscriptionMax): self
    {
        $this->nbInscriptionMax = $nbInscriptionMax;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(?string $infosSortie): self
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }

    public function getMotifAnnulation(): ?string
    {
        return $this->motifAnnulation;
    }

    public function setMotifAnnulation(?string $motifAnnulation): self
    {
        $this->motifAnnulation = $motifAnnulation;

        return $this;
    }

    public function getPhotoSortie(): ?string
    {
        return $this->photoSortie;
    }

    public function setPhotoSortie(?string $photoSortie): self
    {
        $this->photoSortie = $photoSortie;

        return $this;
    }

    public function getOrganisateur(): ?Participants
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Participants $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getEtat(): ?Etats
    {
        return $this->etat;
    }

    public function setEtat(?Etats $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getSite(): ?Sites
    {
        return $this->site;
    }

    public function setSite(?Sites $site): self
    {
        $this->site = $site;

        return $this;
    }


    /**
     * @return Collection<int, Participants>
     */
    public function getParticipe(): Collection
    {
        return $this->participe;
    }

    public function addParticipe(Participants $participe): self
    {
        if (!$this->participe->contains($participe)) {
            $this->participe->add($participe);
            $participe->addEstInscrit($this);
        }

        return $this;
    }

    public function removeParticipe(Participants $participe): self
    {
        if ($this->participe->removeElement($participe)) {
            $participe->removeEstInscrit($this);
        }

        return $this;
    }
}
