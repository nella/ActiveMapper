<?php
namespace App;

require_once __DIR__ . "/tests/bootstrap.php";

use ActiveMapper;
use Nette;

Nette\Debug::enable();

$article = \ActiveMapper\Repository::find('App\Models\Article', 1); //načti článek s ID 2
echo $article->title; //vypíše titulek článku
echo $article->author()->name; //vypíše jméno autora
foreach ($article->tags() as $tag)
{
		echo $tag->name; //vypíše název štítku
}
echo $article->author()->profile()->web; //vypíše web autora
