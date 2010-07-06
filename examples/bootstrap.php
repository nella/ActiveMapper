<?php
namespace App;

require_once __DIR__ . "/../libs/Nette/loader.php";

\Nette\Debug::enable(\Nette\Debug::DEVELOPMENT);
\Nette\Environment::setVariable("tempDir", __DIR__ . "/temp");

$loader = new \Nette\Loaders\RobotLoader();
$loader->addDirectory(__DIR__ . "/../libs");
$loader->addDirectory(__DIR__ . "/../ActiveMapper");
$loader->addDirectory(__DIR__);
$loader->register();

\dibi::connect(array(
	'driver' => "sqlite3",
	'database' => ":memory:",
	'formatDateTime' => "'Y-m-d H:i:s'",
	'lazy' => TRUE,
	'profiler' => TRUE
));

\dibi::loadFile(__DIR__ . "/db.structure.sql");
\dibi::loadFile(__DIR__ . "/db.data.sql");

function echoBeginHtml()
{
	echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><head>'
		.'<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>ActiveMapper Demo</title></head><body>';
}