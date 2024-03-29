<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Participant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"Le nom de famille ne peut pas être vide")]
    #[Assert\Length(max: 50, maxMessage:"Le nom de famille ne peut pas dépasser {{ limit }} caractères")]
    private ?string $surname = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"Le prénom ne peut pas être vide")]
    #[Assert\Length(max: 50, maxMessage:"Le prénom ne peut pas dépasser {{ limit }} caractères")]
    private ?string $firstName = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"Le numéro de téléphone ne peut pas être vide")]
    #[Assert\Length(max: 50, maxMessage:"Le numéro de téléphone ne peut pas dépasser {{ limit }} caractères")]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 50, unique: true)]
    #[Assert\NotBlank(message:"L'email ne peut pas être vide")]
    #[Assert\Email(message:"L'email '{{ value }}' n'est pas valide.")]
    #[Assert\Length(max: 50, maxMessage:"L'email ne peut pas dépasser {{ limit }} caractères")]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\OneToMany(targetEntity: ParticipantGoOut::class, mappedBy: 'Participant')]
    private Collection $participantGoOuts;

    #[ORM\ManyToOne(inversedBy: 'participants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Site $site = null;

    #[ORM\OneToMany(targetEntity: GoOut::class, mappedBy: 'participant')]
    private Collection $goOut;

    #[ORM\OneToOne(inversedBy: 'participant', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageProfileName = null;

    public function __construct()
    {
        $this->participantGoOuts = new ArrayCollection();
        $this->goOut = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

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
            $participantGoOut->setParticipant($this);
        }

        return $this;
    }

    public function removeParticipantGoOut(ParticipantGoOut $participantGoOut): static
    {
        if ($this->participantGoOuts->removeElement($participantGoOut)) {
            // set the owning side to null (unless already changed)
            if ($participantGoOut->getParticipant() === $this) {
                $participantGoOut->setParticipant(null);
            }
        }

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

    /**
     * @return Collection<int, GoOut>
     */
    public function getGoOut(): Collection
    {
        return $this->goOut;
    }

    public function addGoOut(GoOut $goOut): static
    {
        if (!$this->goOut->contains($goOut)) {
            $this->goOut->add($goOut);
            $goOut->setOrganizer($this);
        }

        return $this;
    }

    public function removeGoOut(GoOut $goOut): static
    {
        if ($this->goOut->removeElement($goOut)) {
            // set the owning side to null (unless already changed)
            if ($goOut->getOrganizer() === $this) {
                $goOut->setOrganizer(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
        if ($user->getParticipant() !== $this) {
            $user->setParticipant($this);
        }
    }

    public function getImageProfileName(): ?string
    {
        return $this->imageProfileName;
    }

    public function setImageProfileName(?string $imageProfileName): static
    {
        $this->imageProfileName = $imageProfileName;

        return $this;
    }

}
