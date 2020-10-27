<?php

namespace App\Entity;

use App\Repository\StatsDevelopRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatsDevelopRepository::class)
 */
class StatsDevelop
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $projet;

    /**
     * @ORM\Column(type="boolean")
     */
    private $recompense;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Duree;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjet(): ?string
    {
        return $this->projet;
    }

    public function setProjet(string $projet): self
    {
        $this->projet = $projet;

        return $this;
    }

    public function getRecompense(): ?bool
    {
        return $this->recompense;
    }

    public function setRecompense(bool $recompense): self
    {
        $this->recompense = $recompense;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->Duree;
    }

    public function setDuree(?string $Duree): self
    {
        $this->Duree = $Duree;

        return $this;
    }
}
