<?php declare(strict_types = 1);

use Monolog\Logger;

return [
	'displayErrorDetails' => true, // set to false in production
	'addContentLengthHeader' => false, // Allow the web server to send the content-length header

	'urls' => [
	],

	// Monolog settings
	'logger' => [
		'name' => 'kpmg_exploring',
		'path' => __DIR__ . '/../data/logs/app.log',
		'level' => Logger::DEBUG,
	],

	'paths' => [
		'views' => __DIR__ . '/../views/',
		'cache' => __DIR__ . '/../data/cache/',
	],

	'dev' => true,
];