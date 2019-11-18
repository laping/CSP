<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SystemeRepository")
 * @UniqueEntity(
 * fields={"nom"},
 * message="Le nom de ce système existe déjà ! Veuillez le modifier."
 * )
 */
class Systeme
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
     * @ORM\OneToMany(targetEntity="App\Entity\Version", mappedBy="systeme")
     */
    private $version;

    public function __construct()
    {
        $this->version = new ArrayCollection();
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
     * @return Collection|version[]
     */
    public function getVersion(): Collection
    {
        return $this->version;
    }

    public function addVersion(version $version): self
    {
        if (!$this->version->contains($version)) {
            $this->version[] = $version;
            $version->setSysteme($this);
        }

        return $this;
    }

    public function removeVersion(version $version): self
    {
        if ($this->version->contains($version)) {
            $this->version->removeElement($version);
            // set the owning side to null (unless already changed)
            if ($version->getSysteme() === $this) {
                $version->setSysteme(null);
            }
        }

        return $this;
    }
}
