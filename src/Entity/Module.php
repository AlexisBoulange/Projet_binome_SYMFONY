<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModuleRepository::class)
 */
class Module
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $libelle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descriptif;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="modules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity=Programmer::class, mappedBy="modules", orphanRemoval=true)
     */
    private $programmers;

    public function __construct()
    {
        $this->programmers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(?string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection|Programmer[]
     */
    public function getProgrammers(): Collection
    {
        return $this->programmers;
    }

    public function addProgrammer(Programmer $programmer): self
    {
        if (!$this->programmers->contains($programmer)) {
            $this->programmers[] = $programmer;
            $programmer->setModules($this);
        }

        return $this;
    }

    public function removeProgrammer(Programmer $programmer): self
    {
        if ($this->programmers->removeElement($programmer)) {
            // set the owning side to null (unless already changed)
            if ($programmer->getModules() === $this) {
                $programmer->setModules(null);
            }
        }

        return $this;
    }
}
