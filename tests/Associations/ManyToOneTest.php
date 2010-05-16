<?php
namespace App\Models;

require_once __DIR__ . "/../bootstrap.php";
require_once "PHPUnit/Framework.php";

class ManyToOneTest extends \PHPUnit_Framework_TestCase
{
	public function testMapped1()
	{
		$object = new \ActiveMapper\Associations\ManyToOne('App\Models\Article', 'App\Models\Author');
		$this->assertEquals('author', $object->getName());
		$this->assertEquals('author', $object->name);

		$this->assertEquals('App\Models\Article', $object->getSourceEntity());
		$this->assertEquals('App\Models\Article', $object->sourceEntity);
		$this->assertEquals('App\Models\Author', $object->getTargetEntity());
		$this->assertEquals('App\Models\Author', $object->targetEntity);
		
		$this->assertEquals('articles', $object->getSourceTable());
		$this->assertEquals('articles', $object->sourceTable);
		$this->assertEquals('authors', $object->getTargetTable());
		$this->assertEquals('authors', $object->targetTable);

		$this->assertEquals('author_id', $object->getSourceColumn());
		$this->assertEquals('author_id', $object->sourceColumn);
		$this->assertEquals('id', $object->getTargetColumn());
		$this->assertEquals('id', $object->targetColumn);
	}
	
	public function testMapped2()
	{
		$object = new \ActiveMapper\Associations\ManyToOne('App\Models\Article', 'App\Models\Author', 'test', 'name', 'title');
		$this->assertEquals('test', $object->getName());
		$this->assertEquals('test', $object->name);

		$this->assertEquals('App\Models\Article', $object->getSourceEntity());
		$this->assertEquals('App\Models\Article', $object->sourceEntity);
		$this->assertEquals('App\Models\Author', $object->getTargetEntity());
		$this->assertEquals('App\Models\Author', $object->targetEntity);
		
		$this->assertEquals('articles', $object->getSourceTable());
		$this->assertEquals('articles', $object->sourceTable);
		$this->assertEquals('authors', $object->getTargetTable());
		$this->assertEquals('authors', $object->targetTable);

		$this->assertEquals('title', $object->getSourceColumn());
		$this->assertEquals('title', $object->sourceColumn);
		$this->assertEquals('name', $object->getTargetColumn());
		$this->assertEquals('name', $object->targetColumn);
	}
	
	public function testNotExistTargetColumnException()
	{
		$this->setExpectedException('InvalidArgumentException');
		new \ActiveMapper\Associations\ManyToOne('App\Models\Article', 'App\Models\Author', 'test', 'exception', 'title');
	}
	
	public function testGetData1()
	{
		$article = \ActiveMapper\Repository::find('App\Models\Article', 3);
		$this->assertEquals(new \App\Models\Author(array('id' => 2, 'name' => "John Doe")), $article->author());
	}
}