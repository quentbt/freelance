<?php

namespace App\Entity;

use App\Repository\MessagerieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessagerieRepository::class)]
class Messagerie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'messageries')]
    private ?User $User = null;

    #[ORM\OneToMany(mappedBy: 'messagerie', targetEntity: Message::class, cascade: ['persist', 'remove'])]
    private Collection $messages;

    #[ORM\ManyToOne(inversedBy: 'messagerieReceveur')]
    private ?User $UserReceveur = null;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setMessagerie($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getMessagerie() === $this) {
                $message->setMessagerie(null);
            }
        }

        return $this;
    }

    public function getUserReceveur(): ?User
    {
        return $this->UserReceveur;
    }

    public function setUserReceveur(?User $UserReceveur): self
    {
        $this->UserReceveur = $UserReceveur;

        return $this;
    }
}
