<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EtatRepository")
 * @UniqueEntity(
 * fields={"nom"},
 * message="Le nom de cet état existe déjà ! Veuillez le modifier."
 * )
 */
class Etat
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
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Machine", mappedBy="etat")
     */
    private $machine;

  

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Couleur", inversedBy="etat")
     */
    private $couleur;

    public function __construct()
    {
        $this->machine = new ArrayCollection();
        $this->peripherique = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|machine[]
     */
    public function getMachine(): Collection
    {
        return $this->machine;
    }

    public function addMachine(machine $machine): self
    {
        if (!$this->machine->contains($machine)) {
            $this->machine[] = $machine;
            $machine->setEtat($this);
        }

        return $this;
    }

    public function removeMachine(machine $machine): self
    {
        if ($this->machine->contains($machine)) {
            $this->machine->removeElement($machine);
            // set the owning side to null (unless already changed)
            if ($machine->getEtat() === $this) {
                $machine->setEtat(null);
            }
        }

        return $this;
    }

    

    public function getCouleur(): ?Couleur
    {
        return $this->couleur;
    }

    public function setCouleur(?Couleur $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

}
