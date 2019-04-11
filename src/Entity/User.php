<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Cet email est déjà utilisé.."
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank(
	 *     message="Veuillez renseigner votre prénom"
	 * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank(
	 *     message="Veuillez renseigner votre nom de famille"
	 * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
	 * @Assert\Email(
	 *     message="Adresse mail incorrect ! Veuillez renseigner un email valide"
	 * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
	 * @Assert\Url(
	 *     message="Veuillez donner une url valide"
	 * )
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

	/**
	 * @Assert\EqualTo(
	 *     propertyPath="hash",
	 *     message="Vos mots de passes ne sont pas identique"
	 * )
	 */
    private $confirmPassword;

    /**
     * @ORM\Column(type="string", length=255)
	 * @Assert\Length(
	 *     min="15",
	 *     minMessage="Votre introduction doit faire au minimum 15 caractères",
	 *     max="100",
	 *     maxMessage="Votre introduction doit faire au maximum 100 caractères"
	 * )
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
	 * @Assert\Length(
	 *     min="60",
	 *     minMessage="Votre introduction doit faire au minimum 60 caractères",
	 *     max="1000",
	 *     maxMessage="Votre introduction doit faire au maximum 1000 caractères"
	 * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Annonce", mappedBy="author")
     */
    private $annonces;

	/**
	 * Execution de la fonction avant qu'elle soit persister / update (avant que l'annonce ne soit créer) #AnnotationsDeCycleDeVie
	 * @ORM\PrePersist()
	 * @ORM\PreUpdate()
	 * @return void
	 */
	public function initSlug()
	{
		if(empty($this->slug)) // Si nous n'avons pas de Slug cela nous en crée un
		{
			$slugify = new Slugify();
			$this->slug = $slugify->slugify($this->firstname. ' ' . $this->lastname);
		}
	}

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

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

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setAuthor($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->contains($annonce)) {
            $this->annonces->removeElement($annonce);
            // set the owning side to null (unless already changed)
            if ($annonce->getAuthor() === $this) {
                $annonce->setAuthor(null);
            }
        }

        return $this;
    }

	/**
	 * Returns the roles granted to the user.
	 *
	 *     public function getRoles()
	 *     {
	 *         return ['ROLE_USER'];
	 *     }
	 *
	 * Alternatively, the roles might be stored on a ``roles`` property,
	 * and populated in any number of different ways when the user object
	 * is created.
	 *
	 * @return (Role|string)[] The user roles
	 */
	public function getRoles()
	{
		return ['ROLE_USER'];
	}

	/**
	 * Returns the password used to authenticate the user.
	 *
	 * This should be the encoded password. On authentication, a plain-text
	 * password will be salted, encoded, and then compared to this value.
	 *
	 * @return string The password
	 */
	public function getPassword()
	{
		return $this->hash;
	}

	/**
	 * Returns the salt that was originally used to encode the password.
	 *
	 * This can return null if the password was not encoded using a salt.
	 *
	 * @return string|null The salt
	 */
	public function getSalt()
	{
		// TODO: Implement getSalt() method.
	}

	/**
	 * Returns the username used to authenticate the user.
	 *
	 * @return string The username
	 */
	public function getUsername()
	{
		return $this->email;
	}

	/**
	 * Removes sensitive data from the user.
	 *
	 * This is important if, at any given point, sensitive information like
	 * the plain-text password is stored on this object.
	 */
	public function eraseCredentials()
	{
		// TODO: Implement eraseCredentials() method.
	}

	/**
	 * @param mixed $confirmPassword
	 * @return User
	 */
	public function setConfirmPassword($confirmPassword)
	{
		$this->confirmPassword = $confirmPassword;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getConfirmPassword()
	{
		return $this->confirmPassword;
	}
}
