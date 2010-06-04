<?php
namespace ActiveMapperTests\Associations;

use ActiveMapper\Associations\OneToMany;

require_once __DIR__ . "/../bootstrap.php";
require_once "PHPUnit/Framework.php";

class OneToManyTest extends \PHPUnit_Framework_TestCase
{
	public function testMapped1()
	{
		$object = new OneToMany('App\Models\Author', 'App\Models\Article');
		$this->assertEquals('articles', $object->getName());
		$this->assertEquals('articles', $object->name);

		$this->assertEquals('App\Models\Author', $object->getSourceEntity());
		$this->assertEquals('App\Models\Author', $object->sourceEntity);
		$this->assertEquals('App\Models\Article', $object->getTargetEntity());
		$this->assertEquals('App\Models\Article', $object->targetEntity);
		
		$this->assertEquals('authors', $object->getSourceTable());
		$this->assertEquals('authors', $object->sourceTable);
		$this->assertEquals('articles', $object->getTargetTable());
		$this->assertEquals('articles', $object->targetTable);

		$this->assertEquals('id', $object->getSourceColumn());
		$this->assertEquals('id', $object->sourceColumn);
		$this->assertEquals('author_id', $object->getTargetColumn());
		$this->assertEquals('author_id', $object->targetColumn);
	}
	
	public function testMapped2()
	{
		$object = new OneToMany('App\Models\Author', 'App\Models\Article', 'test', 'title', 'name');
		$this->assertEquals('test', $object->getName());
		$this->assertEquals('test', $object->name);

		$this->assertEquals('App\Models\Author', $object->getSourceEntity());
		$this->assertEquals('App\Models\Author', $object->sourceEntity);
		$this->assertEquals('App\Models\Article', $object->getTargetEntity());
		$this->assertEquals('App\Models\Article', $object->targetEntity);
		
		$this->assertEquals('authors', $object->getSourceTable());
		$this->assertEquals('authors', $object->sourceTable);
		$this->assertEquals('articles', $object->getTargetTable());
		$this->assertEquals('articles', $object->targetTable);

		$this->assertEquals('name', $object->getSourceColumn());
		$this->assertEquals('name', $object->sourceColumn);
		$this->assertEquals('title', $object->getTargetColumn());
		$this->assertEquals('title', $object->targetColumn);
	}
	
	public function testNotExistSourceColumnException()
	{
		$this->setExpectedException('InvalidArgumentException');
		new OneToMany('App\Models\Author', 'App\Models\Article', 'test', 'title', 'exception');
	}
	
	public function testGetData1()
	{
		$author = \ActiveMapper\Repository::factory('App\Models\Author')->find(2);
		$articles = $author->articles()->fetchAll(NULL, 1);
		$this->assertEquals(new \App\Models\Article(array(
			'id' => 2, 
			'title' => "Sdílej své obrázky jednoduše Ukáz.at", 
			'content' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed scelerisque, ligula ac rhoncus consequat,"
				." quam eros bibendum nunc, vitae egestas sem nulla vel enim. Ut id euismod velit. Nullam nec faucibus arcu. Maecenas"
				." faucibus pulvinar venenatis. Mauris commodo adipiscing lectus sit amet viverra. Aliquam porta nisi vitae eros tempus"
				." non fermentum augue varius. Cras libero diam, condimentum quis rutrum quis, imperdiet nec mi. Quisque laoreet, augue eu"
				." venenatis tristique, tellus lectus fermentum nisl, at pretium purus augue at risus. Nulla sed fermentum ligula."
				." Nulla ut libero velit, eu dignissim dui. Morbi lacus mi, bibendum vitae sodales vitae, blandit eu tellus. Morbi"
				." imperdiet semper quam, vitae tempor nulla fermentum rhoncus. Praesent ultricies imperdiet leo, nec facilisis ligula"
				." ornare in. Phasellus a sagittis nulla. Aenean hendrerit arcu leo, a elementum justo. Sed ipsum lacus, ullamcorper et"
				." mattis sit amet, luctus vulputate arcu. Suspendisse ut ligula non turpis pellentesque aliquam quis eu ante. ", 
			'author_id' => 2, 
			'create' => "2010-01-31", 
			'price' => 0.0, 
			'public' => FALSE
			
		)), $articles[0]);
	}
}
