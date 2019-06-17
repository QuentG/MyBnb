<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use App\Service\Pagination;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminUserController extends AbstractController
{

	/**
	 * @var UserRepository
	 */
	private $repository;

	/**
	 * @var ObjectManager
	 */
	private $manager;

	/**
	 * AdminUserController constructor.
	 *
	 * @param UserRepository $repository
	 * @param ObjectManager $manager
	 */
	public function __construct(UserRepository $repository, ObjectManager $manager)
	{
		$this->repository = $repository;
		$this->manager = $manager;
	}

	/**
	 * @param int $page
	 * @param Pagination $pagination
	 * @return Response
	 */
    public function index(int $page, Pagination $pagination)
    {

    	$pagination->setEntityClass(User::class)
			->setCurrentPage($page);

        return $this->render('admin/user/index.html.twig', [
        	'pagination' => $pagination
        ]);
    }

	public function deleteUser(User $user)
	{
		$this->manager->remove($user);
		$this->manager->flush();

		$this->addFlash('success', "Le'utilisateur <strong>{$user->getFullName()}</strong> a bien été supprimé !");

		return $this->redirectToRoute('admin_users_index');
	}
}
