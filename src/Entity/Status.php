<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $libelle = null;

    #[ORM\OneToMany(targetEntity: GoOut::class, mappedBy: 'status')]
    private Collection $goOut;

    public function __construct()
    {
        $this->goOut = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

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
            $goOut->setStatus($this);
        }

        return $this;
    }

    public function removeGoOut(GoOut $goOut): static
    {
        if ($this->goOut->removeElement($goOut)) {
            // set the owning side to null (unless already changed)
            if ($goOut->getStatus() === $this) {
                $goOut->setStatus(null);
            }
        }

        return $this;
    }
}
