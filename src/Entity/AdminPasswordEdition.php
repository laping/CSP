<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class AdminPasswordEdition
{
    /**
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit faire au moins 8 caractères !")
     */
    private $nouveauPassword;

    /**
     * @Assert\EqualTo(propertyPath="nouveauPassword", message="Vous n'avez pas correctement confirmé votre nouveau mot de passe !")
     */
    private $confirmPassword;


    public function getNouveauPassword(): ?string
    {
        return $this->nouveauPassword;
    }

    public function setNouveauPassword(string $nouveauPassword): self
    {
        $this->nouveauPassword = $nouveauPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
