<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProjetsRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

/**
 * @ORM\Entity(repositoryClass=ProjetsRepository::class)
 * @Vich\Uploadable
 */
class Projets
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
    private $titre;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="projets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\Column(type="text")
     */
    private $besoinsClient;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $propositionCommercial;

    /**
     * @Vich\UploadableField(mapping="featured_documents", fileNameProperty="propositionCommercial")
     * @var File
     */
    private $propositionCommercialFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cahierCharge;

    /**
     * @Vich\UploadableField(mapping="featured_documents", fileNameProperty="cahierCharge")
     * @var File
     */
    private $cahierChargeFile;

    /**
     * @var \DateTime $createdAt
     * 
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
    */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getClient(): ?Users
    {
        return $this->client;
    }

    public function setClient(?Users $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getBesoinsClient(): ?string
    {
        return $this->besoinsClient;
    }

    public function setBesoinsClient(string $besoinsClient): self
    {
        $this->besoinsClient = $besoinsClient;

        return $this;
    }

    public function setPropositionCommercialFile(File $propositionCommercialFile = null)
    {
        $this->propositionCommercialFile = $propositionCommercialFile;

        if ($propositionCommercialFile) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getPropositionCommercialFile()
    {
        return $this->propositionCommercialFile;
    }

    public function getPropositionCommercial(): ?string
    {
        return $this->propositionCommercial;
    }

    public function setCahierChargeFile(File $cahierChargeFile = null)
    {
        $this->cahierChargeFile = $cahierChargeFile;

        if ($cahierChargeFile) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getCahierChargeFile()
    {
        return $this->cahierChargeFile;
    }

    public function setPropositionCommercial(?string $propositionCommercial): self
    {
        $this->propositionCommercial = $propositionCommercial;

        return $this;
    }

    public function getCahierCharge(): ?string
    {
        return $this->cahierCharge;
    }

    public function setCahierCharge(?string $cahierCharge): self
    {
        $this->cahierCharge = $cahierCharge;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

}
