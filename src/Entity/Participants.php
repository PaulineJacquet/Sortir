<?php

namespace App\Entity;

use App\Repository\ParticipantsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;

#[UniqueEntity(fields: ['mail'], message: 'Un compte avec cet email existe déjà !')]
#[UniqueEntity(fields: ['pseudo'], message: 'Un compte avec ce pseudo existe déjà !')]
#[ORM\Entity(repositoryClass: ParticipantsRepository::class)]
class Participants implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(options:['unsigned'=>true])]
    private ?int $id = null;

    #[ORM\Column(length: 30, unique: true)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 30)]
    private ?string $nom = null;

    #[ORM\Column(length: 30)]
    private ?string $prenom = null;
    #[Assert\GreaterThan(9)]
    #[Assert\LessThan(11)]
    #[ORM\Column(length: 10, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 20, unique:true)]
    private ?string $mail = null;

    #[ORM\Column(length: 100)]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $administrateur = null;

    #[ORM\Column]
    private ?bool $actif = null;

    #[ORM\Column]
    private ?string $photo=null;

    #[ORM\OneToMany(mappedBy: 'organisateur', targetEntity: Sorties::class)]
    private Collection $sorties;

    #[ORM\ManyToOne(targetEntity: Sites::class, inversedBy: 'participant')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sites $site = null;

    #[ORM\ManyToMany(targetEntity: Sorties::class, inversedBy: 'participe')]
    private Collection $estInscrit;

    // #[ORM\Column]
    //private array $roles;

    public function __construct()
    {
        $this->inscription = new ArrayCollection();
        $this->sorties = new ArrayCollection();
        $this->estInscrit = new ArrayCollection();
        if($this->getPhoto()===null){
            $this->setPhoto('avatar.jpg');
        }
    }

    /**
     * @var string The hashed password
     */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {

        if ($this->administrateur == true) {
            $roles[] = 'ROLE_ADMIN';
        }else{
            $roles[] = 'ROLE_USER';
        }        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function isAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * @return Collection<int, Sorties>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSortie(Sorties $sortie): self
    {
        if (!$this->sorties->contains($sortie)) {
            $this->sorties->add($sortie);
            $sortie->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSortie(Sorties $sortie): self
    {
        if ($this->sorties->removeElement($sortie)) {
            // set the owning side to null (unless already changed)
            if ($sortie->getOrganisateur() === $this) {
                $sortie->setOrganisateur(null);
            }
        }

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
     * @return Collection<int, Sorties>
     */
    public function getEstInscrit(): Collection
    {
        return $this->estInscrit;
    }

    public function addEstInscrit(Sorties $estInscrit): self
    {
        if (!$this->estInscrit->contains($estInscrit)) {
            $this->estInscrit->add($estInscrit);
        }

        return $this;
    }

    public function removeEstInscrit(Sorties $estInscrit): self
    {
        $this->estInscrit->removeElement($estInscrit);

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): void
    {
        $this->photo = $photo;
    }


}
