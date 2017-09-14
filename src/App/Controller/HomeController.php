<?php declare(strict_types = 1);

namespace App\Controller;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
* Home controller
*/
class HomeController extends AbstractController
{
	/**
	 * Home
	 *
	 * @param  ServerRequestInterface $request   Request object.
	 * @param  ResponseInterface      $response  Response object.
	 *
	 * @return ResponseInterface                 Response
	 */
	public function getIndexAction(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$name = $request->getAttribute('param_1', null);

		return $this->getTwig()->render($response, 'home.twig', [
			'name' => $name,
		]);
	}
}