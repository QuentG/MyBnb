<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{

	/**
	 * @var ObjectManager
	 */
	private $manager;
	/**
	 * @var UserRepository
	 */
	private $repository;

	public function __construct(UserRepository $repository, ObjectManager $manager)
	{
		$this->repository = $repository;
		$this->manager = $manager;
	}

	/**
	 * @param AuthenticationUtils $authenticationUtils
	 * @return Response
	 */
	public function login(AuthenticationUtils $authenticationUtils)
	{
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastname = $authenticationUtils->getLastUsername();

        return $this->render('account/login.html.twig', [
        	'error' => $error !== null,
			'lastname' => $lastname
		]);
    }

	/**
	 * @return void
	 */
	public function logout() {}

	/**
	 * @param Request $request
	 * @param UserPasswordEncoderInterface $encoder
	 * @return Response
	 */
	public function register(Request $request, UserPasswordEncoderInterface $encoder)
	{
		$user = new User();

		$form = $this->createForm(RegistrationType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			// Encode password
			$hash = $encoder->encodePassword($user, $user->getHash());
			$user->setHash($hash);

			$this->manager->persist($user);
			$this->manager->flush();

			$this->addFlash('success',
				'Compte crée avec success ! Vous pouvez dès à présent vous connectez.');

			return $this->redirectToRoute('login');
		}

		return $this->render('account/registration.html.twig', [
			'form' => $form->createView()
		]);
	}
}
