<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    	// Utilisation de la librairie Faker pour faire des fausses données "réaliste"
    	$faker = Factory::create('fr_FR'); // Langue FR

		// Une trentaine d'annonces
    	for ($i = 1; $i <= 30; $i++)
    	{
    		$annonce = new Annonce();

    		$title = $faker->sentence();
    		$coverImage = $faker->imageUrl(1000, 350);
			$intro = $faker->paragraph(2);
			$content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

			$annonce->setTitle($title)
				->setCoverImage($coverImage)
				->setIntroduction($intro)
				->setContent($content)
				->setPrice(mt_rand(50, 300))
				->setRooms(mt_rand(2, 5));

			$manager->persist($annonce);
		}

        $manager->flush();
    }
}
