<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SiteRepository::class)]
class Site
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"Le nom ne peut pas être vide")]
    #[Assert\Length(max: 50, maxMessage:"Le nom ne peut pas dépasser {{ limit }} caractères")]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Participant::class, mappedBy: 'site')]
    private Collection $participants;

    #[ORM\OneToMany(targetEntity: GoOut::class, mappedBy: 'site')]
    private Collection $goOut;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->goOut = new ArrayCollection();
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

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->setSite($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): static
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getSite() === $this) {
                $participant->setSite(null);
            }
        }

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
            $goOut->setSite($this);
        }

        return $this;
    }

    public function removeGoOut(GoOut $goOut): static
    {
        if ($this->goOut->removeElement($goOut)) {
            // set the owning side to null (unless already changed)
            if ($goOut->getSite() === $this) {
                $goOut->setSite(null);
            }
        }

        return $this;
    }
}
