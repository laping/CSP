<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MachineRepository")
 * @UniqueEntity(
 * fields={"id"},
 * message="L'ID de cette machine existe déjà ! Veuillez le modifier."
 * )
 */
class Machine
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="Champ requis")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $serial;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="machine")
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Etat", inversedBy="machine")
     * @ORM\JoinColumn(name="etat_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Modele", inversedBy="machine")
     * @ORM\JoinColumn(name="modele_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    private $modele;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Assert\Ip
     */
    private $ip;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Emplacement", inversedBy="machine")
     * @ORM\JoinColumn(name="emplacement_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    private $emplacement;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Version", inversedBy="machine")
     *  @ORM\JoinColumn(name="version_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    private $version;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $clavier;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $souris;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $enceintes;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $chargeur;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $telecommande;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $ecran;

    public function __construct()
    {
        $this->peripheriques = new ArrayCollection();
    }

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

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

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

    public function getModele(): ?Modele
    {
        return $this->modele;
    }

    public function setModele(?Modele $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }


    public function getEmplacement(): ?Emplacement
    {
        return $this->emplacement;
    }

    public function setEmplacement(?Emplacement $emplacement): self
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    public function getVersion(): ?Version
    {
        return $this->version;
    }

    public function setVersion(?Version $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getClavier(): ?bool
    {
        return $this->clavier;
    }

    public function setClavier(?bool $clavier): self
    {
        $this->clavier = $clavier;

        return $this;
    }

    public function getSouris(): ?bool
    {
        return $this->souris;
    }

    public function setSouris(?bool $souris): self
    {
        $this->souris = $souris;

        return $this;
    }

    public function getEnceintes(): ?bool
    {
        return $this->enceintes;
    }

    public function setEnceintes(?bool $enceintes): self
    {
        $this->enceintes = $enceintes;

        return $this;
    }

    public function getChargeur(): ?bool
    {
        return $this->chargeur;
    }

    public function setChargeur(?bool $chargeur): self
    {
        $this->chargeur = $chargeur;

        return $this;
    }

    public function getTelecommande(): ?bool
    {
        return $this->telecommande;
    }

    public function setTelecommande(?bool $telecommande): self
    {
        $this->telecommande = $telecommande;

        return $this;
    }

    public function getEcran(): ?bool
    {
        return $this->ecran;
    }

    public function setEcran(?bool $ecran): self
    {
        $this->ecran = $ecran;

        return $this;
    }
}
