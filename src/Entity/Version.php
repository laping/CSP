<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VersionRepository")
 * @UniqueEntity(
 * fields={"nom"},
 * message="Le nom de cette version existe déjà ! Veuillez le modifier."
 * )
 */
class Version
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
     * @ORM\OneToMany(targetEntity="App\Entity\Machine", mappedBy="version")
     */
    private $machine;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Systeme", inversedBy="version")
     * @ORM\JoinColumn(name="systeme_id", referencedColumnName="id", onDelete="cascade", nullable=true)
     */
    private $systeme;

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
            $machine->setVersion($this);
        }

        return $this;
    }

    public function removeMachine(machine $machine): self
    {
        if ($this->machine->contains($machine)) {
            $this->machine->removeElement($machine);
            // set the owning side to null (unless already changed)
            if ($machine->getVersion() === $this) {
                $machine->setVersion(null);
            }
        }

        return $this;
    }

    public function getSysteme(): ?Systeme
    {
        return $this->systeme;
    }

    public function setSysteme(?Systeme $systeme): self
    {
        $this->systeme = $systeme;

        return $this;
    }
}
