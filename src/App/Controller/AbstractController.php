<?php declare(strict_types = 1);

namespace App\Controller;

use Interop\Container\ContainerInterface;

class AbstractController
{
	/**
	 * @var ContainerInterface  Service container
	 */
	public $services;

	/**
	 * Class constructor
	 *
	 * @param ContainerInterface $services Service container
	 */
	function __construct(ContainerInterface $services)
	{
		$this->services = $services;
	}

	public function getTwig()
	{
		return $this->services->get('twig');
	}
}