<?php
namespace App;

require_once __DIR__ . "/tests/bootstrap.php";

$article = \ActiveMapper\Repository::find('App\Models\Article', 1); //načti článek s ID 2


echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><head>'
	.'<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>ActiveMapper - Test</title></head><body><code><pre>';
echo $article->title . " - " //vypíše titulek článku
	. $article->author()->name; //vypíše jméno autora
echo "\n\n"; $i = 0;
foreach ($article->tags() as $tag)
{
		if ($i!=0)
			echo ", ";
		
		echo $tag->name; //vypíše název štítku
		
		$i++;
}
echo "\n\n" . $article->author()->profile()->web; //vypíše web autora
