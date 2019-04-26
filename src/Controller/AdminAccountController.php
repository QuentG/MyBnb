<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminAccountController extends AbstractController
{
	/**
	 * Connexion admin !
	 *
	 * @return Response
	 */
	public function login()
    {


        return $this->render('admin/account/login.html.twig', [

        ]);
    }
}
