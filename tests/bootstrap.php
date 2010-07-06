<?php
namespace ActiveMapperTests;

require_once __DIR__ . "/../libs/Nette/loader.php";

\Nette\Debug::enable(\Nette\Debug::DEVELOPMENT);
\Nette\Environment::setVariable("tempDir", __DIR__ . "/_temp");

$loader = new \Nette\Loaders\RobotLoader();
$loader->addDirectory(__DIR__ . "/../libs");
$loader->addDirectory(__DIR__ . "/../ActiveMapper");
$loader->addDirectory(__DIR__ . "/../examples/Models");
$loader->register();

\dibi::connect(array(
	'driver' => "sqlite3",
	'database' => ":memory:",
	'formatDateTime' => "'Y-m-d H:i:s'",
	'lazy' => TRUE,
	'profiler' => TRUE
));

/**
 * Author entity test factory
 *
 * @param array $data
 * @return App\Models\Author
 */
function author(array $data)
{
	$author = new \App\Models\Author;
	$ref = \Nette\Reflection\ClassReflection::from('App\Models\Author');
	if (isset($data['id'])) {
		$prop = $ref->getProperty('id');
		$prop->setAccessible(TRUE);
		$prop->setValue($author, $data['id']);
		$prop->setAccessible(FALSE);
	}
	if (isset($data['name'])) {
		$prop = $ref->getProperty('name');
		$prop->setAccessible(TRUE);
		$prop->setValue($author, $data['name']);
		$prop->setAccessible(FALSE);
	}
	if (isset($data['web'])) {
		$prop = $ref->getProperty('web');
		$prop->setAccessible(TRUE);
		$prop->setValue($author, $data['web']);
		$prop->setAccessible(FALSE);
	}
	return $author;
}

\dibi::loadFile(__DIR__ . "/db.structure.sql");
\dibi::loadFile(__DIR__ . "/db.data.sql");