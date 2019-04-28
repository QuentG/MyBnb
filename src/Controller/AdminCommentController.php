<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use App\Service\Pagination;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminCommentController extends AbstractController
{

	/**
	 * @var CommentRepository
	 */
	private $repository;

	/**
	 * @var ObjectManager
	 */
	private $manager;

	/**
	 * AdminAnnonceController constructor.
	 *
	 * @param CommentRepository $repository
	 * @param ObjectManager $manager
	 */
	public function __construct(CommentRepository $repository, ObjectManager $manager)
	{
		$this->repository = $repository;
		$this->manager = $manager;
	}

	/**
	 * Affiche tous les commentaires de toutes les annonces
	 *
	 * @param int $page
	 * @param Pagination $pagination
	 * @return Response
	 */
	public function index(int $page, Pagination $pagination)
    {
    	$pagination->setEntityClass(Comment::class)
			->setCurrentPage($page);

        return $this->render('admin/comment/index.html.twig', [
			'pagination' => $pagination
        ]);
    }

	/**
	 * Edition d'un commentaire
	 *
	 * @param Comment $comment
	 * @param Request $request
	 * @return Response
	 */
	public function editComment(Comment $comment, Request $request)
	{
		$form = $this->createForm(AdminCommentType::class, $comment);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {

			$this->manager->persist($comment);
			$this->manager->flush();

			$this->addFlash('success', "Le commentaire n°{$comment->getId()} a bien été modifié !");

			return $this->redirectToRoute('admin_comments_index');
		}

		return $this->render('admin/comment/edit.html.twig', [
			'comment' => $comment,
			'form' => $form->createView()
		]);
	}

	/**
	 * Supprime un commentaire
	 *
	 * @param Comment $comment
	 * @return Response
	 */
	public function deleteComment(Comment $comment)
	{
		$this->manager->remove($comment);
		$this->manager->flush();

		$this->addFlash('success', "Le commentaire de <strong>{$comment->getAuthor()->getFullName()}</strong> a bien été supprimé !");

		return $this->redirectToRoute('admin_comments_index');
	}
}
