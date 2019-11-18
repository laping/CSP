<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 * @UniqueEntity(
 * fields={"nom"},
 * message="Cet utilisateur existe déjà !"
 * )
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner le nom de l'utilisateur")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @Assert\EqualTo(propertyPath="hash",message="Vous n'avez pas correctement confirmé votre mot de passe !")
     */
    public $passwordConfirm;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="auteur")
     */
    private $messages;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="utilisateur")
     */
    private $utilisateurRoles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentaire", mappedBy="auteur")
     */
    private $commentaires;


    

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->utilisateurRoles = new ArrayCollection();
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

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setAuteur($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getAuteur() === $this) {
                $message->setAuteur(null);
            }
        }

        return $this;
    }

    public function getRoles()
    {
        $roles= $this->utilisateurRoles->map(function ($role){

            return $role->getNom();
        })->toArray();

        $roles[] = 'ROLE_USER';

        return $roles;
    }

    public function getPassword()
    {
        return $this->hash;
    }

    public function getSalt(){}
    
    public function getUsername()
    {
        return $this->nom;
    } 
    
    public function eraseCredentials(){}

    /**
     * @return Collection|Role[]
     */
    public function getUtilisateurRoles(): Collection
    {
        return $this->utilisateurRoles;
    }

    public function addUtilisateurRole(Role $utilisateurRole): self
    {
        if (!$this->utilisateurRoles->contains($utilisateurRole)) {
            $this->utilisateurRoles[] = $utilisateurRole;
            $utilisateurRole->addUtilisateur($this);
        }

        return $this;
    }

    public function removeUtilisateurRole(Role $utilisateurRole): self
    {
        if ($this->utilisateurRoles->contains($utilisateurRole)) {
            $this->utilisateurRoles->removeElement($utilisateurRole);
            $utilisateurRole->removeUtilisateur($this);
        }

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setAuteur($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getAuteur() === $this) {
                $commentaire->setAuteur(null);
            }
        }

        return $this;
    }

   
    
}
