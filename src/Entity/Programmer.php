<?php

namespace App\Entity;

use App\Repository\ProgrammerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProgrammerRepository::class)
 */
class Programmer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Session::class, inversedBy="programmers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sessions;

    /**
     * @ORM\ManyToOne(targetEntity=Module::class, inversedBy="programmers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $modules;

    /**
     * @ORM\Column(type="integer")
     */
    private $duree;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSessions(): ?Session
    {
        return $this->sessions;
    }

    public function setSessions(?Session $sessions): self
    {
        $this->sessions = $sessions;

        return $this;
    }

    public function getModules(): ?Module
    {
        return $this->modules;
    }

    public function setModules(?Module $modules): self
    {
        $this->modules = $modules;

        return $this;
    }

    public function __toString()
    {
        return $this->getModules().' : '.$this->getDuree(). ' jour(s), ';
    }

    /**
     * Get the value of duree
     */ 
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set the value of duree
     *
     * @return  self
     */ 
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }
}
