<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
	/**
	 * @param User $user
	 * @return Response
	 */
    public function index(User $user)
    {
        return $this->render('user/index.html.twig', [
			'user' => $user
        ]);
    }
}
