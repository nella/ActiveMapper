<?php
namespace App;

require_once __DIR__ . "/bootstrap.php";

use Nette\Debug;

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
	Debug::dump($author);
}

echo "<h1>Author by ID #3</h1>";
// Get author by id
$author = $em->find('App\Models\Author', 3);

Debug::dump($author);

/********************************************************************************************************************************/
// Benchmark data
Debug::barDump($mappingTime = number_format(Debug::timer('benchmark')*1000, 1, '.', ' ')."ms", "Mapping Time");
Debug::barDump($mappingMemory = number_format((memory_get_peak_usage() - $memory) / 1000, 1, '.', ' ')."kB", "Mapping Memory");

$benchMarkData = "mapping time: $mappingTime mapping memory: $mappingMemory "
		."total time: ".number_format((microtime(TRUE)-Debug::$time)*1000, 1, '.', ' ')."ms "
		."total memory: ".number_format(memory_get_peak_usage() / 1000, 1, '.', ' ')."kB";
file_put_contents(__DIR__ . "/benchmark.log", date("r")." # ".$benchMarkData.PHP_EOL, FILE_APPEND);