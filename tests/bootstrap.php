<?php
require_once __DIR__ . "/../libs/Nette/loader.php";

Nette\Debug::enable(Nette\Debug::DEVELOPMENT);
Nette\Environment::setVariable("tempDir", __DIR__ . "/_temp");

$loader = new Nette\Loaders\RobotLoader();
$loader->addDirectory(__DIR__ . "/../libs");
$loader->addDirectory(__DIR__ . "/../ActiveMapper");
$loader->addDirectory(__DIR__ . "/../examples/Models");
$loader->register();

dibi::connect(array(
	'driver' => "sqlite3",
	'database' => ":memory:",
	'formatDateTime' => "'Y-m-d H:i:s'",
	'lazy' => TRUE,
	'profiler' => TRUE
));
//dibi::getProfiler()->setFile('log.sql');

dibi::loadFile(__DIR__ . "/db.structure.sql");
dibi::loadFile(__DIR__ . "/db.data.sql");