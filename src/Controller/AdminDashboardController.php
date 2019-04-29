<?php

namespace App\Controller;

use App\Service\Stats;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminDashboardController extends AbstractController
{

	/**
	 * @var ObjectManager
	 */
	private $manager;

	/**
	 * AdminDashboardController constructor.
	 *
	 * @param ObjectManager $manager
	 */
	public function __construct(ObjectManager $manager)
	{
		$this->manager = $manager;
	}

	/**
	 * @param Stats $stats
	 * @return Response
	 */
	public function index(Stats $stats)
    {
    	$best = $stats->getAnnoncesStats('DESC');
    	$worst = $stats->getAnnoncesStats('ASC');
		$stats = $stats->getStats();

        return $this->render('admin/dashboard/index.html.twig', [
			'best' => $best,
			'worst' => $worst,
			'stats' => $stats
        ]);
    }
}
