<?php declare(strict_types = 1);

use Monolog\Handler\StreamHandler;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Logger;
use Slim\Container;
use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;

$settings = require __DIR__ . '/settings.php';

$container = new Container(['settings' => $settings]);

$services = [
	'logger' => function ($container)
	{
		$settings = $container->get('settings')['logger'];

		$logger = new Logger($settings['name'], [
			new StreamHandler($settings['path'], Logger::DEBUG),
			new StreamHandler($settings['path'], Logger::WARNING),
		], [new PsrLogMessageProcessor]);

		return $logger;
	},

	'twig' => function ($container)
	{
		$settings = $container->get('settings')['paths'];

		$twig = new \Slim\Views\Twig($settings['views'], [
			// 'cache' => $settings['cache']
		]);

		// Instantiate and add Slim specific extension
		$basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');

		$twig->addExtension(new Slim\Views\TwigExtension($container['router'], ''));
		$twig->addExtension(new App\Twig\TwigExtension($container->get('settings')));

		return $twig;
	},

	'guzzle' => function ($container)
	{
		$stack = \GuzzleHttp\HandlerStack::create();

		$stack->unshift(
			\GuzzleHttp\Middleware::log(
				$container->get('logger'),
				new \GuzzleHttp\MessageFormatter('{method} {uri} HTTP/{version} {req_body} {code}')
			)
		);

		$guzzle = new Client([
			'handler' => $stack,
		]);

		return $guzzle;
	},

	// Slim services
	// 'errorHandler' => function($container)
	// {
	// 	return function ($request, $response, $exception) use ($container) {
	// 		$container['logger']->error('{error}', [ 'error' => $exception->getMessage() ]);
	// 		return $container['response']->withJson(['error' => $exception->getMessage()])->withStatus(400);
	// 	};
	// },

	// 'notFoundHandler' => function($container)
	// {
	// 	return function ($request, $response) use ($container) {
	// 		return $container['response']->withJson(['error' => 'Page not found'])->withStatus(404);
	// 	};
	// },

	// 'notAllowedHandler' => function($container)
	// {
	// 	return function ($request, $response, $methods) use ($container) {
	// 		return $container['response']->withJson(['error' => 'Method must be one of: ' . implode(', ', $methods)])->withStatus(405);
	// 	};
	// },

	// 'phpErrorHandler' => function($container)
	// {
	// 	return function ($request, $response, $exception) use ($container) {
	// 		$container['logger']->error('PHP ERROR: {file} {line} {error}', [
	// 			'file' => $exception->getFile(),
	// 			'line' => $exception->getLine(),
	// 			'error' => $exception->getMessage(),
	// 		]);

	// 		return $container['response']->withJson([
	// 			'error' => 'Something went wrong.'
	// 		])->withStatus(500);
	// 	};
	// },
];

foreach ($services as $name => $callable)
{
	$container[$name] = $callable;
}

return $container;