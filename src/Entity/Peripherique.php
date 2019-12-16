<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PeripheriqueRepository")
 * @UniqueEntity(
 * fields={"id"},
 * message="L'ID de ce périphérique existe déjà ! Veuillez le modifier."
 * )
 */
class Peripherique
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string",length=30)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $serial;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Machine", inversedBy="peripheriques")
     * @ORM\JoinColumn(name="machine_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    private $machine;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="peripherique")
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Etat", inversedBy="peripherique")
     * @ORM\JoinColumn(name="etat_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $etat;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getSerial(): ?string
    {
        return $this->serial;
    }

    public function setSerial(?string $serial): self
    {
        $this->serial = $serial;

        return $this;
    }

    public function getMachine(): ?machine
    {
        return $this->machine;
    }

    public function setMachine(?machine $machine): self
    {
        $this->machine = $machine;

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

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
    

}
