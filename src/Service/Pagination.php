<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

/**
 * Classe de Pagination qui extrait toute notion de calcul et de récupération de données de nos controllers
 *
 * Elle nécessite après instanciation qu'on lui passe l'entité sur laquelle on souhaite travailler
 * Exemple : $pagination->setEntityClass(Comment::class);
 */
class Pagination {

	/**
	 * Le nom de l'entité sur laquelle on veut effectuer une pagination
	 *
	 * @var string
	 */
	private $entityClass;

	/**
	 * Le nombre d'enregistrement à récupérer
	 * Par défault il est à 10
	 *
	 * @var integer
	 */
	private $limit = 10;

	/**
	 * La page sur laquelle on se trouve actuellement
	 *
	 * @var integer
	 */
	private $currentPage = 1;

	/**
	 * Le nom de la route que l'on veut utiliser pour les boutons de la navigation
	 *
	 * @var string
	 */
	private $route;

	/**
	 * Le chemin vers le template qui contient la pagination
	 *
	 * @var string
	 */
	private $templatePath;

	/**
	 * Le manager de Doctrine qui nous permet par exemple de trouver le repository dont on a besoin
	 *
	 * @var ObjectManager
	 */
	private $manager;

	/**
	 * Le moteur de template Twig qui va permettre de générer le rendu de la pagination
	 *
	 * @var Environment
	 */
	private $environment;

	/**
	 * La requete qui va nous permettre de récupérer automatiquement le nom de la route
	 * sur laquelle nous nous trouvons
	 *
	 * @var RequestStack
	 */
	private $requestStack;

	/**
	 * Pagination constructor.
	 *
	 * N'oubliez pas de configurer votre fichier services.yaml afin que Symfony sache quelle valeur
	 * utiliser pour le $templatePath !
	 *
	 * @param ObjectManager $manager
	 * @param Environment $environment
	 * @param RequestStack $requestStack
	 * @param string $templatePath
	 */
	public function __construct(ObjectManager $manager, Environment $environment, RequestStack $requestStack, string $templatePath)
	{
		$this->manager = $manager;
		$this->environment = $environment;
		$this->requestStack = $requestStack;
		$this->templatePath = $templatePath;
	}

	/**
	 * Récupère les données paginées pour une entité spécifique
	 *
	 * Elle se sert de Doctrine afin de récupérer le repository pour l'entité spécifiée
	 * puis grâce au repository et à sa fonction findBy() on récupère les données dans une
	 * certaine limite et en partant d'un offset
	 *
	 * @throws Exception si la propriété $entityClass n'est pas définie !
	 *
	 * @return array
	 */
	public function getData()
	{
		if(empty($this->entityClass)) {
			throw new Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer ! Utilisez la méthode setEntityClass() de votre objet Pagination !");
		}
		// Calcul offset
		// 1 * 10 = 10 - 10 = 0 && 2 * 10 = 20 - 10 = 10 etc..
		$offset = $this->currentPage * $this->limit - $this->limit;

		return $this->manager
			->getRepository($this->entityClass)
			->findBy([], [], $this->limit, $offset);
	}

	/**
	 * Récupère le nombre de pages qui existent sur une entité particulière
	 *
	 * Elle se sert de Doctrine pour récupérer le repository qui correspond à l'entité que l'on souhaite
	 * paginer (voir la propriété $entityClass) puis elle trouve le nombre total d'enregistrements grâce
	 * à la fonction findAll() du repository
	 *
	 * @throws Exception si la propriété $entityClass n'est pas configurée !
	 *
	 * @return int
	 */
	public function getPages(): int
	{
		if(empty($this->entityClass)) {
			throw new Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer ! Utilisez la méthode setEntityClass() de votre objet Pagination !");
		}

		$repo = $this->manager->getRepository($this->entityClass);

		$total = count($repo->findAll());
		$pages = ceil($total / $this->limit); // 2,4 => ceil() = 3
		return $pages;
	}

	/**
	 * Affiche le rendu de la navigation au sein d'un template twig !
	 *
	 * On se sert du moteur de rendu Twig afin de compiler le template qui se trouve au chemin
	 * de notre propriété $templatePath, en lui passant les variables :
	 *
	 * - page  => La page actuelle sur laquelle on se trouve
	 * - pages => le nombre total de pages qui existent
	 * - route => le nom de la route à utiliser pour les liens de navigation
	 *
	 * Attention : cette fonction ne retourne rien, elle affiche directement le rendu
	 *
	 */
	public function render()
	{
		$this->environment->display($this->templatePath, [
			'page' => $this->currentPage,
			'pages' => $this->getPages(),
			// Récupération de la route actuelle grâce à la RequestStack
			'route' => $this->route = $this->requestStack->getCurrentRequest()->attributes->get('_route')
		]);
	}

	/**
	 * @param mixed $entityClass
	 * @return Pagination
	 */
	public function setEntityClass($entityClass)
	{
		$this->entityClass = $entityClass;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getEntityClass()
	{
		return $this->entityClass;
	}

	/**
	 * @param int $limit
	 * @return Pagination
	 */
	public function setLimit(int $limit): Pagination
	{
		$this->limit = $limit;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getLimit(): int
	{
		return $this->limit;
	}

	/**
	 * @param int $currentPage
	 * @return Pagination
	 */
	public function setCurrentPage(int $currentPage): Pagination
	{
		$this->currentPage = $currentPage;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCurrentPage(): int
	{
		return $this->currentPage;
	}

	/**
	 * @param mixed $route
	 * @return Pagination
	 */
	public function setRoute($route)
	{
		$this->route = $route;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getRoute()
	{
		return $this->route;
	}

	/**
	 * @param mixed $templatePath
	 * @return Pagination
	 */
	public function setTemplatePath($templatePath)
	{
		$this->templatePath = $templatePath;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getTemplatePath()
	{
		return $this->templatePath;
	}


}