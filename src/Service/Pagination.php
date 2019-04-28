<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class Pagination {

	private $entityClass;
	private $limit = 10;
	private $currentPage = 1;
	private $manager;

	public function __construct(ObjectManager $manager)
	{
		$this->manager = $manager;
	}

	/**
	 * @return object[]
	 */
	public function getData() {
		// Calcul offset
		// 1 * 10 = 10 - 10 = 0 && 2 * 10 = 20 - 10 = 10 etc..
		$offset = $this->currentPage * $this->limit - $this->limit;

		$repo = $this->manager->getRepository($this->entityClass);
		$data = $repo->findBy([], [], $this->limit, $offset);

		return $data;
	}

	/**
	 * @return float
	 */
	public function getPages() {
		$repo = $this->manager->getRepository($this->entityClass);

		$total = count($repo->findAll());
		$pages = ceil($total / $this->limit); // 2,4 => ceil() = 3
		return $pages;
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


}