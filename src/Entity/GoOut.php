<?php

namespace App\Entity;

use App\Repository\GoOutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GoOutRepository::class)]
class GoOut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDateTime = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $limitDateInscription = null;

    #[ORM\Column]
    private ?int $maxNbInscriptions = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: ParticipantGoOut::class, mappedBy: 'goOut')]
    private Collection $participantGoOuts;

    #[ORM\ManyToOne(inversedBy: 'goOut')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Status $status = null;

    #[ORM\ManyToOne(inversedBy: 'goOut')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Place $place = null;

    #[ORM\ManyToOne(inversedBy: 'goOut')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Site $site = null;

    #[ORM\ManyToOne(inversedBy: 'goOut')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Participant $participant = null;

    public function __construct()
    {
        $this->participantGoOuts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStartDateTime(): ?\DateTimeInterface
    {
        return $this->startDateTime;
    }

    public function setStartDateTime(\DateTimeInterface $startDateTime): static
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): void
    {
        $this->duration = $duration;
    }

    public function getLimitDateInscription(): ?\DateTimeInterface
    {
        return $this->limitDateInscription;
    }

    public function setLimitDateInscription(\DateTimeInterface $limitDateInscription): static
    {
        $this->limitDateInscription = $limitDateInscription;

        return $this;
    }

    public function getMaxNbInscriptions(): ?int
    {
        return $this->maxNbInscriptions;
    }

    public function setMaxNbInscriptions(int $maxNbInscriptions): static
    {
        $this->maxNbInscriptions = $maxNbInscriptions;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, ParticipantGoOut>
     */
    public function getParticipantGoOuts(): Collection
    {
        return $this->participantGoOuts;
    }

    public function addParticipantGoOut(ParticipantGoOut $participantGoOut): static
    {
        if (!$this->participantGoOuts->contains($participantGoOut)) {
            $this->participantGoOuts->add($participantGoOut);
            $participantGoOut->setGoOut($this);
        }

        return $this;
    }

    public function removeParticipantGoOut(ParticipantGoOut $participantGoOut): static
    {
        if ($this->participantGoOuts->removeElement($participantGoOut)) {
            // set the owning side to null (unless already changed)
            if ($participantGoOut->getGoOut() === $this) {
                $participantGoOut->setGoOut(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): static
    {
        $this->site = $site;

        return $this;
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
}
