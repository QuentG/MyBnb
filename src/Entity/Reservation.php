<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reserveur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Annonce", inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $annonce;

    /**
     * @ORM\Column(type="datetime")
	 * @Assert\Date(message="Attention vous devez rentrer une date !")
	 * @Assert\GreaterThan(
	 *     "today",
	 *     message="La date d'arrivée doit être ultérieure à la date d'aujourd'hui !",
	 *	   groups={"front"})
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
	 * @Assert\Date(message="Attention vous devez rentrer une date !")
	 * @Assert\GreaterThan(
	 *     propertyPath="startDate",
	 *     message="La date de départ doit être plus éloignée que la date d'arrivée !")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

	/**
	 * @ORM\PrePersist()
	 * @ORM\PreUpdate()
	 *
	 * @throws Exception
	 */
    public function prePersist()
	{
		if(empty($this->createdAt))
		{
			$this->createdAt = new DateTime();
		}
		if(empty($this->amount))
		{
			// prix de l'annonce * nbr jour
			$this->amount = $this->annonce->getPrice() * $this->getDuration();
		}
	}

	public function getDuration()
	{
		// Objet de type DateInterval
		$diff = $this->endDate->diff($this->startDate);
		return $diff->days;
	}

	/**
	 * Récupère un tableau des journées qui correspondent à ma réservation
	 *
	 * @return array Un tableau d'objets DateTime représentant les jours de la réservation
	 */
	public function getDays()
	{
		$result = range(
			$this->getStartDate()->getTimestamp(),
			$this->getEndDate()->getTimestamp(),
			24 * 60 * 60
		);

		$days = array_map(function ($dayTimestamp) {
			return new DateTime(date('Y-m-d', $dayTimestamp));
		}, $result);

		return $days;
	}

	/**
	 *
	 */
	public function isPossibleDates()
	{
		// Les dates qui sont impossibles pour l'annonce
		$notAvailableDays = $this->annonce->getNotAvailableDays();
		// Compare les dates choisies avec les dates impossibles
		$reservationDays = $this->getDays();

		$formatDay = function ($day) {
			return $day->format('Y-m-d');
		};

		// Tableau qui contient des string des journées
		$days = array_map($formatDay, $reservationDays);
		$notAvailable = array_map($formatDay, $notAvailableDays);

		foreach ($days as $day)
		{
			if (array_search($day, $notAvailable) !== false) return false;
		}

		return true;
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReserveur(): ?User
    {
        return $this->reserveur;
    }

    public function setReserveur(?User $reserveur): self
    {
        $this->reserveur = $reserveur;

        return $this;
    }

    public function getAnnonce(): ?Annonce
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonce $annonce): self
    {
        $this->annonce = $annonce;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
