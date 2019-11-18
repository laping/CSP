<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategorieRepository")
 * @UniqueEntity(
 * fields={"nom"},
 * message="Le nom de cette catégorie existe déjà ! Veuillez le modifier."
 * )
 */
class Categorie
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
     * @ORM\OneToMany(targetEntity="App\Entity\Machine", mappedBy="categorie")
     */
    private $machine;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Peripherique", mappedBy="categorie")
     */
    private $peripherique;

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
            $machine->setCategorie($this);
        }

        return $this;
    }

    public function removeMachine(machine $machine): self
    {
        if ($this->machine->contains($machine)) {
            $this->machine->removeElement($machine);
            // set the owning side to null (unless already changed)
            if ($machine->getCategorie() === $this) {
                $machine->setCategorie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|peripherique[]
     */
    public function getPeripherique(): Collection
    {
        return $this->peripherique;
    }

    public function addPeripherique(peripherique $peripherique): self
    {
        if (!$this->peripherique->contains($peripherique)) {
            $this->peripherique[] = $peripherique;
            $peripherique->setCategorie($this);
        }

        return $this;
    }

    public function removePeripherique(peripherique $peripherique): self
    {
        if ($this->peripherique->contains($peripherique)) {
            $this->peripherique->removeElement($peripherique);
            // set the owning side to null (unless already changed)
            if ($peripherique->getCategorie() === $this) {
                $peripherique->setCategorie(null);
            }
        }

        return $this;
    }

}
