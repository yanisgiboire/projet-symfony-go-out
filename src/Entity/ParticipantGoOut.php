<?php

namespace App\Entity;

use App\Repository\ParticipantGoOutRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipantGoOutRepository::class)]
class ParticipantGoOut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'participantGoOuts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Participant $participant = null;

    #[ORM\ManyToOne(inversedBy: 'participantGoOuts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GoOut $goOut = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParticipant(): ?Participant
    {
        return $this->participant;
    }

    public function setParticipant(?Participant $participant): static
    {
        $this->participant = $participant;

        return $this;
    }

    public function getGoOut(): ?GoOut
    {
        return $this->goOut;
    }

    public function setGoOut(?GoOut $goOut): static
    {
        $this->goOut = $goOut;

        return $this;
    }
}
