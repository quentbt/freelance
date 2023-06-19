<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    private ?string $message = null;

    #[ORM\ManyToOne(inversedBy: 'messageEnvoyeur')]
    private ?User $UserEnvoyeur = null;

    #[ORM\ManyToOne(inversedBy: 'messageReceveur')]
    private ?User $UserReceveur = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Messagerie $messagerie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getUserEnvoyeur(): ?User
    {
        return $this->UserEnvoyeur;
    }

    public function setUserEnvoyeur(?User $UserEnvoyeur): self
    {
        $this->UserEnvoyeur = $UserEnvoyeur;

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

    public function getMessagerie(): ?Messagerie
    {
        return $this->messagerie;
    }

    public function setMessagerie(?Messagerie $messagerie): self
    {
        $this->messagerie = $messagerie;

        return $this;
    }
}
