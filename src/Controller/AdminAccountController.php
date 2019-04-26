<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
	/**
	 * Connexion admin !
	 *
	 * @param AuthenticationUtils $authenticationUtils
	 * @return Response
	 */
	public function login(AuthenticationUtils $authenticationUtils)
    {
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastname = $authenticationUtils->getLastUsername();

        return $this->render('admin/account/login.html.twig', [
        	'error' => $error !== null,
			'lastname' => $lastname
        ]);
    }

	/**
	 * Déconnexion admin !
	 *
	 * @return void
	 */
	public function logout () {}
}
