<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmplacementRepository")
 * @UniqueEntity(
 * fields={"nom"},
 * message="Le nom de cet emplacement existe déjà ! Veuillez le modifier."
 * )
 */
class Emplacement
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
     * @ORM\OneToMany(targetEntity="App\Entity\Machine", mappedBy="emplacement")
     */
    private $machine;

    public function __construct()
    {
        $this->machine = new ArrayCollection();
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
            $machine->setEmplacement($this);
        }

        return $this;
    }

    public function removeMachine(machine $machine): self
    {
        if ($this->machine->contains($machine)) {
            $this->machine->removeElement($machine);
            // set the owning side to null (unless already changed)
            if ($machine->getEmplacement() === $this) {
                $machine->setEmplacement(null);
            }
        }

        return $this;
    }
}
