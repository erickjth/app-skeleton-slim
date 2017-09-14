<?php declare(strict_types = 1);

namespace App\Middleware;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
* MVC Middleware class
*/
class MvcRouterMiddleware
{
	/**
	 * @var ContainerInterface  Service container
	 */
	public $container;

	/**
	 * Class constructor
	 *
	 * @param ContainerInterface $container Service container
	 */
	function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	/**
	 * Invokable method.
	 *
	 * @param  ServerRequestInterface $request   Request object.
	 * @param  ResponseInterface      $response  Response object.
	 *
	 * @return ResponseInterface                 Response
	 */
	public function __invoke(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$namespace = 'App\\Controller\\';

		$controller = $request->getAttribute('controller', 'home');
		$action = $request->getAttribute('action', 'index');

		if ($request->getAttribute('params', false) !== false)
		{
			$params = explode('/', $request->getAttribute('params'));

			foreach ($params as $key => $value)
			{
				$request = $request->withAttribute('param_' . ($key + 1), $value);
			}
		}

		$controller = ucfirst($controller) . 'Controller';
		$method = strtolower($request->getMethod()) . str_replace('-', '', ucfirst($action)). 'Action';

		$classReflector = new \ReflectionClass($namespace . $controller);

		$instance = $classReflector->newInstanceArgs([$this->container]);

		return $classReflector->getMethod($method)->invoke($instance, $request, $response);
	}
}