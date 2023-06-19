<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $name = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Service::class)]
    private Collection $services;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: Messagerie::class)]
    private Collection $messageries;

    #[ORM\OneToMany(mappedBy: 'UserEnvoyeur', targetEntity: Message::class)]
    private Collection $messageEnvoyeur;

    #[ORM\OneToMany(mappedBy: 'UserReceveur', targetEntity: Message::class)]
    private Collection $messageReceveur;

    #[ORM\OneToMany(mappedBy: 'UserReceveur', targetEntity: Messagerie::class)]
    private Collection $messagerieReceveur;

    #[ORM\OneToMany(mappedBy: 'id_user', targetEntity: Achat::class)]
    private Collection $achats;

    public function __construct()
    {
        $this->services = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->commentaires = new ArrayCollection();
        $this->messageEnvoyeur = new ArrayCollection();
        $this->messageReceveur = new ArrayCollection();
        $this->messageries = new ArrayCollection();
        $this->messagerieReceveur = new ArrayCollection();
        $this->achats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
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
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setUser($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getUser() === $this) {
                $service->setUser(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }


    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
            }
        }
        return $this;
    }

    public function getMessagerie(): ?Messagerie
    {
        return $this->messagerie;
    }

    public function setMessagerie(?Messagerie $messagerie): self
    {
        // unset the owning side of the relation if necessary
        if ($messagerie === null && $this->messagerie !== null) {
            $this->messagerie->setMessagerieUser(null);
        }

        // set the owning side of the relation if necessary
        if ($messagerie !== null && $messagerie->getMessagerieUser() !== $this) {
            $messagerie->setMessagerieUser($this);
        }

        $this->messagerie = $messagerie;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessageEnvoyeur(): Collection
    {
        return $this->messageEnvoyeur;
    }

    public function addMessageEnvoyeur(Message $messageEnvoyeur): self
    {
        if (!$this->messageEnvoyeur->contains($messageEnvoyeur)) {
            $this->messageEnvoyeur->add($messageEnvoyeur);
            $messageEnvoyeur->setUserEnvoyeur($this);
        }

        return $this;
    }

    public function removeMessageEnvoyeur(Message $messageEnvoyeur): self
    {
        if ($this->messageEnvoyeur->removeElement($messageEnvoyeur)) {
            // set the owning side to null (unless already changed)
            if ($messageEnvoyeur->getUserEnvoyeur() === $this) {
                $messageEnvoyeur->setUserEnvoyeur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessageReceveur(): Collection
    {
        return $this->messageReceveur;
    }

    public function addMessageReceveur(Message $messageReceveur): self
    {
        if (!$this->messageReceveur->contains($messageReceveur)) {
            $this->messageReceveur->add($messageReceveur);
            $messageReceveur->setUserReceveur($this);
        }

        return $this;
    }

    public function removeMessageReceveur(Message $messageReceveur): self
    {
        if ($this->messageReceveur->removeElement($messageReceveur)) {
            // set the owning side to null (unless already changed)
            if ($messageReceveur->getUserReceveur() === $this) {
                $messageReceveur->setUserReceveur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Messagerie>
     */
    public function getMessageries(): Collection
    {
        return $this->messageries;
    }

    public function addMessagery(Messagerie $messagery): self
    {
        if (!$this->messageries->contains($messagery)) {
            $this->messageries->add($messagery);
            $messagery->setUser($this);
        }

        return $this;
    }

    public function removeMessagery(Messagerie $messagery): self
    {
        if ($this->messageries->removeElement($messagery)) {
            // set the owning side to null (unless already changed)
            if ($messagery->getUser() === $this) {
                $messagery->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Messagerie>
     */
    public function getMessagerieReceveur(): Collection
    {
        return $this->messagerieReceveur;
    }

    public function addMessagerieReceveur(Messagerie $messagerieReceveur): self
    {
        if (!$this->messagerieReceveur->contains($messagerieReceveur)) {
            $this->messagerieReceveur->add($messagerieReceveur);
            $messagerieReceveur->setUserReceveur($this);
        }

        return $this;
    }

    public function removeMessagerieReceveur(Messagerie $messagerieReceveur): self
    {
        if ($this->messagerieReceveur->removeElement($messagerieReceveur)) {
            // set the owning side to null (unless already changed)
            if ($messagerieReceveur->getUserReceveur() === $this) {
                $messagerieReceveur->setUserReceveur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Achat>
     */
    public function getAchats(): Collection
    {
        return $this->achats;
    }

    public function addAchat(Achat $achat): self
    {
        if (!$this->achats->contains($achat)) {
            $this->achats->add($achat);
            $achat->setIdUser($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): self
    {
        if ($this->achats->removeElement($achat)) {
            // set the owning side to null (unless already changed)
            if ($achat->getIdUser() === $this) {
                $achat->setIdUser(null);
            }
        }

        return $this;
    }
}
