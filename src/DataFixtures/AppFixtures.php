<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\Image;
use App\Entity\Reservation;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $encoder;

	/**
	 * AppFixtures constructor.
	 * @param UserPasswordEncoderInterface $encoder
	 */
	public function __construct(UserPasswordEncoderInterface $encoder)
	{
		$this->encoder = $encoder;
	}

	public function load(ObjectManager $manager)
    {
    	// Utilisation de la librairie Faker pour faire des fausses données "réaliste"
    	$faker = Factory::create('fr_FR'); // Langue FR

		$roleAdmin = new Role();
		$roleAdmin->setTitle("ROLE_ADMIN");

		$manager->persist($roleAdmin);

		$userAdmin = new User();
		$userAdmin->setFirstname("Quentin")
			->setLastname("Gans")
			->setEmail('contact@quentingans.fr')
			->setHash($this->encoder->encodePassword($userAdmin, 'password'))
			->setPicture('https://avatars2.githubusercontent.com/u/33687186?s=460&v=4')
			->setIntroduction($faker->sentence)
			->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
			->addUserRole($roleAdmin);

		$manager->persist($userAdmin);

		$users = [];
		$genres = ['male', 'female'];

		// Une dixaine d'utilisateurs
		for ($u = 1; $u <= 10; $u++)
		{
			$user = new User();

			$genre = $faker->randomElement($genres);

			$picture = "https://randomuser.me/api/portraits/";
			$pictureId = $faker->numberBetween(1, 99) . '.jpg';

			$picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

			$hash = $this->encoder->encodePassword($user, 'password');

			$user->setFirstname($faker->firstName($genre))
				->setLastname($faker->lastName)
				->setEmail($faker->email)
				->setIntroduction($faker->sentence)
				->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
				->setHash($hash)
				->setPicture($picture);

			$manager->persist($user);
			// On ajout dans le tableau $users les $user
			$users[] = $user;
		}

		// Une trentaine d'annonces
    	for ($i = 1; $i <= 30; $i++)
    	{
    		$annonce = new Annonce();

    		$title = $faker->sentence();
    		$coverImage = $faker->imageUrl(1000, 350);
			$intro = $faker->paragraph(2);
			$content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';
			$author = $users[mt_rand(0, count($users) - 1)];

			$annonce->setTitle($title)
				->setCoverImage($coverImage)
				->setIntroduction($intro)
				->setContent($content)
				->setPrice(mt_rand(50, 300))
				->setRooms(mt_rand(2, 5))
				->setAuthor($author);

			// Remplissage des img
			for ($j = 1; $j <= mt_rand(2,5); $j++)
			{
				$img = new Image();
				$img->setUrl($faker->imageUrl())
					->setCaption($faker->sentence())
					->setAnnonce($annonce);

				$manager->persist($img);
			}

			// Réservations
			for ($r = 1; $r <= mt_rand(0, 10); $r++)
			{
				$reservation = new Reservation();

				$createdAt = $faker->dateTimeBetween('- 6 months');
				$startDate = $faker->dateTimeBetween('- 3 months');
				$duration = mt_rand(3, 10);

				$endDate = (clone $startDate)->modify("+$duration days");
				$amount = $annonce->getPrice() * $duration;

				$reserveur = $users[mt_rand(0, count($users) -1)];
				$comment = $faker->paragraph();

				$reservation->setReserveur($reserveur)
					->setAnnonce($annonce)
					->setStartDate($startDate)
					->setEndDate($endDate)
					->setCreatedAt($createdAt)
					->setAmount($amount)
					->setComment($comment);

				$manager->persist($reservation);

			}

			$manager->persist($annonce);
		}

        $manager->flush();
    }
}
