<?php

/**
 * My Application bootstrap file.
 */
use Nette\Application\Routers\Route;


// Step 1: Load Nette Framework
// this allows load Nette Framework classes automatically so that
// you don't have to litter your code with 'require' statements
require LIBS_DIR . '/Nette/nette.min.php';


// Step 2: Configure application
$configurator = new Nette\Config\Configurator;

// 2a) Enable Nette Debugger for error visualisation & logging
$configurator->enableDebugger(__DIR__ . '/../log');

// 2b) Enable RobotLoader - this will load all classes automatically
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->createRobotLoader()
	->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->register();

// 2c) Set baseUri for use in config.neon
$baseUri = dirname($_SERVER['SCRIPT_NAME']);
$configurator->addParameters(array('baseUri' => $baseUri));

// 2d) Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config/config.neon');


// Step 3: Create DI container
$container = $configurator->createContainer();


// Step 4: Setup application router
$container->router[] = new Route('index.php', 'Homepage:default', Route::ONE_WAY);
$container->router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');


// Step 5: Run the application!
$container->application->run();
