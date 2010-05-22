<?php
namespace App;

require_once __DIR__ . "/../Nette/loader.php";

use Nette;
use dibi;

Nette\Debug::enable(Nette\Debug::DEVELOPMENT);
Nette\Environment::setVariable("tempDir", __DIR__ . "/temp");
$loader = new Nette\Loaders\RobotLoader();
$loader->addDirectory(__DIR__ . "/..");
$loader->register();

dibi::connect(array(
	'driver' => "sqlite3",
	'database' => ":memory:",
	'formatDateTime' => "'Y-m-d H:i:s'",
	'lazy' => TRUE,
	'profiler' => TRUE
));
//dibi::getProfiler()->setFile('log.sql');

dibi::loadFile(__DIR__ . "/../db.structure.sql");
dibi::loadFile(__DIR__ . "/../db.data.sql");
\ActiveMapper\ORM::$disableEntityMetaDataCache = TRUE;