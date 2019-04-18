<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnonceRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"title"},
 *     message="Une autre annonce possède déjà ce titre !"
 * )
 */
class Annonce
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
	 * @Assert\Length(min="5", max="30", minMessage="Le titre doit faire minimum 5 caractères", maxMessage="Le titre doit faire maximum 30 caractères")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
	 * @Assert\Length(min="20", max="100", minMessage="L'introduction doit faire minimum 20 caractères", maxMessage="L'introduction doit faire maximum 100 caractères")
	 */
    private $introduction;

    /**
     * @ORM\Column(type="text")
	 * @Assert\Length(min="100", max="1000", minMessage="La description doit faire minimum 100 caractères", maxMessage="La description doit faire maximum 1000 caractères")
	 */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
	 * @Assert\Url()
     */
    private $coverImage;

    /**
     * @ORM\Column(type="integer")
     */
    private $rooms;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="annonce", orphanRemoval=true)
	 * @Assert\Valid() // Permet de valider les images qui sont reliées à l'annonce
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="annonce")
     */
    private $reservations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="annonce", orphanRemoval=true)
     */
    private $comments;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

	/**
	 * Execution de la fonction avant qu'elle soit persister / update (avant que l'annonce ne soit créer) #AnnotationsDeCycleDeVie
	 * @ORM\PrePersist()
	 * @ORM\PreUpdate()
	 * @return void
	 */
	public function initSlug() {

		if(empty($this->slug)) { // Si nous n'avons pas de Slug cela nous en crée un
			$slugify = new Slugify();
			$this->slug = $slugify->slugify($this->title);
		}
	}

	/**
	 * Obtenir les jours qui ne sont pas disponibles pour une annonce
	 *
	 * @return array Un tableau d'objets DateTime représentant les jours d'occupation
	 */
	public function getNotAvailableDays() {
		$notAvailableDays = [];

		foreach($this->reservations as $reservation) {
			// Calcul des jours qui se trouvent entre la date d'arrivée et de départ
			$result = range(
				$reservation->getStartDate()->getTimestamp(),
				$reservation->getEndDate()->getTimestamp(),
				24 * 60 * 60
			);
			// Transformer la tableau de range() en un autre tableau
			$days = array_map(function ($dayTimestamp) {
				// Transforme le Timestamp en une véritable date
				return new DateTime(date('Y-m-d', $dayTimestamp));
			}, $result);

			// Fusionner 2 tableaux
			$notAvailableDays = array_merge($notAvailableDays, $days);
		}

		return $notAvailableDays;
	}

	/**
	 * Obtenir la note moyenne globale des notes pour cette annonce
	 *
	 * @return float|int
	 */
	public function getAverageRatings() {
		// Calcul de la somme des notations
		$sum = array_reduce($this->comments->toArray(), function ($total, $comment) {
			return $total + $comment->getRating();
		}, 0);
		// Division pour avoir la moyenne
		if(count($this->comments) > 0) {
			$moy = $sum / count($this->comments);
			return $moy;
		}

		return 0;
	}

	/**
	 * Récupère le commentaire d'un auteur par rapport à une annonce !
	 *
	 * @param User $author
	 * @return mixed|null
	 */
	public function getCommentFromAuthor(User $author) {

		foreach ($this->comments as $comment) {
			if($comment->getAuthor() === $author) return $comment;
		}

		return null;
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAnnonce($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getAnnonce() === $this) {
                $image->setAnnonce(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setAnnonce($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getAnnonce() === $this) {
                $reservation->setAnnonce(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAnnonce($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAnnonce() === $this) {
                $comment->setAnnonce(null);
            }
        }

        return $this;
    }
}
