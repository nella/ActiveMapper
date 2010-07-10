<?php
namespace App;

require_once __DIR__ . "/bootstrap.php";

use Nette\Debug,
	Nette\Framework,
	dibi;

Debug::timer('benchmark');
$memory = memory_get_peak_usage();
echoBeginHtml();
/********************************************************************************************************************************/

// Setum entity manager
$em = new \ActiveMapper\Manager(\dibi::getConnection());

echo "<h1>All authors</h1>";
// Get all authors
$authors = $em->findAll('App\Models\Author');

foreach ($authors as $author) {
	Debug::dump($author->name);
	Debug::dump($author->blog()->name);
}

echo "<h1>Author by ID #3</h1>";
// Get author by id
$author = $em->find('App\Models\Author', 3);

Debug::dump($author->name);
Debug::dump($author->blog()->name);

/********************************************************************************************************************************/
// Benchmark data
Debug::barDump(Framework::NAME." ".Framework::VERSION." ".Framework::REVISION);
Debug::barDump("dibi ".dibi::VERSION." ".dibi::REVISION);
Debug::barDump($mappingTime = number_format(Debug::timer('benchmark')*1000, 1, '.', ' ')."ms", "Mapping Time");
Debug::barDump($mappingMemory = number_format((memory_get_peak_usage() - $memory) / 1000, 1, '.', ' ')."kB", "Mapping Memory");

echo '<p><a href="http://github.com/Vrtak-CZ/ActiveMapper/blob/master/examples/index.php" target="_blank">'
		.'Show code on GitHub</a> - <a href="http://am.vrtak-cz.net/coverage">Show coverage</a></p>';

$benchMarkData = "mapping time: $mappingTime mapping memory: $mappingMemory "
		."total time: ".number_format((microtime(TRUE)-Debug::$time)*1000, 1, '.', ' ')."ms "
		."total memory: ".number_format(memory_get_peak_usage() / 1000, 1, '.', ' ')."kB";
file_put_contents(__DIR__ . "/benchmark.log", date("r")." # ".$benchMarkData.PHP_EOL, FILE_APPEND);