<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CouleurRepository")
 * @UniqueEntity(
 * fields={"nom"},
 * message="Le nom de cette couleur existe déjà ! Veuillez le modifier."
 * )
 */
class Couleur
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
     * @ORM\Column(type="string", length=255)
     */
    private $rgb;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Etat", mappedBy="couleur")
     */
    private $etat;

    public function __construct()
    {
        $this->etat = new ArrayCollection();
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

    public function getRgb(): ?string
    {
        return $this->rgb;
    }

    public function setRgb(string $rgb): self
    {
        $this->rgb = $rgb;

        return $this;
    }

    /**
     * @return Collection|etat[]
     */
    public function getEtat(): Collection
    {
        return $this->etat;
    }

    public function addEtat(etat $etat): self
    {
        if (!$this->etat->contains($etat)) {
            $this->etat[] = $etat;
            $etat->setCouleur($this);
        }

        return $this;
    }

    public function removeEtat(etat $etat): self
    {
        if ($this->etat->contains($etat)) {
            $this->etat->removeElement($etat);
            // set the owning side to null (unless already changed)
            if ($etat->getCouleur() === $this) {
                $etat->setCouleur(null);
            }
        }

        return $this;
    }
}
