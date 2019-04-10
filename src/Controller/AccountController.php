<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{

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
}
