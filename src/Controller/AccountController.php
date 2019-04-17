<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\UpdatePassword;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\RegistrationType;
use App\Form\UpdatePasswordType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
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
	 * Connexion
	 *
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
	 * Deconnexion
	 *
	 * @return void
	 */
	public function logout() {}

	/**
	 * Inscription
	 *
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

	/**
	 * Edition du profil
	 *
	 * @param Request $request
	 * @return Response
	 *
	 * @IsGranted("ROLE_USER")
	 */
	public function editProfil(Request $request)
	{
		// Récupération du user connecté $this->getUser();
		$form = $this->createForm(AccountType::class, $this->getUser());
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid())
		{
			$this->manager->flush();

			$this->addFlash('success',
				'Votre profil a bien été modifié !');
		}

		return $this->render('account/profil.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * Changer de mot de passe
	 *
	 * @param Request $request
	 * @param UserPasswordEncoderInterface $encoder
	 * @return Response
	 *
	 * @IsGranted("ROLE_USER")
	 */
	public function changePassword(Request $request, UserPasswordEncoderInterface $encoder)
	{
		$updatePassword = new UpdatePassword();
		$user = $this->getUser();

		$form = $this->createForm(UpdatePasswordType::class, $updatePassword);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid())
		{
			if(!password_verify($updatePassword->getOldPassword(), $user->getHash()))
			{
				// Ajout d'une erreur sur un champ en particulier
				$form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel"));
			} else {

				$newPassword = $updatePassword->getNewPassword();
				$hash = $encoder->encodePassword($user, $newPassword);
				$user->setHash($hash);

				$this->manager->persist($user);
				$this->manager->flush();

				$this->addFlash('success',
					'Votre mot de passe a bien été changé');

				return $this->redirectToRoute('home');
			}
		}

		return $this->render('account/password.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * Affiche le profil de l'utilisateur connecté
	 *
	 * @return Response
	 *
	 * @IsGranted("ROLE_USER")
	 */
	public function myAccount()
	{
		return $this->render('user/index.html.twig', [
			'user' => $this->getUser()
		]);
	}

	/**
	 * Affiche toutes les réservations d'un utilisateur
	 *
	 * @return Response
	 */
	public function reservations()
	{
		return $this->render('account/reservations.html.twig');
	}
}
