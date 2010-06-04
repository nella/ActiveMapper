<?php
namespace ActiveMapperTests\Associations;

use ActiveMapper\Associations\OneToOne;

require_once __DIR__ . "/../bootstrap.php";
require_once "PHPUnit/Framework.php";

class OneToOneTest extends \PHPUnit_Framework_TestCase
{
	public function testMapped1()
	{
		$object = new OneToOne('App\Models\Author', 'App\Models\Profile');
		$this->assertEquals('profile', $object->getName());
		$this->assertEquals('profile', $object->name);

		$this->assertEquals('App\Models\Author', $object->getSourceEntity());
		$this->assertEquals('App\Models\Author', $object->sourceEntity);
		$this->assertEquals('App\Models\Profile', $object->getTargetEntity());
		$this->assertEquals('App\Models\Profile', $object->targetEntity);
		
		$this->assertEquals('authors', $object->getSourceTable());
		$this->assertEquals('authors', $object->sourceTable);
		$this->assertEquals('profiles', $object->getTargetTable());
		$this->assertEquals('profiles', $object->targetTable);

		$this->assertEquals('id', $object->getSourceColumn());
		$this->assertEquals('id', $object->sourceColumn);
		$this->assertEquals('author_id', $object->getTargetColumn());
		$this->assertEquals('author_id', $object->targetColumn);
	}
	
	public function testMapped2()
	{
		$object = new OneToOne('App\Models\Author', 'App\Models\Profile', TRUE, 'test', 'web', 'name');
		$this->assertEquals('test', $object->getName());
		$this->assertEquals('test', $object->name);

		$this->assertEquals('App\Models\Author', $object->getSourceEntity());
		$this->assertEquals('App\Models\Author', $object->sourceEntity);
		$this->assertEquals('App\Models\Profile', $object->getTargetEntity());
		$this->assertEquals('App\Models\Profile', $object->targetEntity);
		
		$this->assertEquals('authors', $object->getSourceTable());
		$this->assertEquals('authors', $object->sourceTable);
		$this->assertEquals('profiles', $object->getTargetTable());
		$this->assertEquals('profiles', $object->targetTable);

		$this->assertEquals('name', $object->getSourceColumn());
		$this->assertEquals('name', $object->sourceColumn);
		$this->assertEquals('web', $object->getTargetColumn());
		$this->assertEquals('web', $object->targetColumn);
	}
	
	public function testNotExistSourceColumnException()
	{
		$this->setExpectedException('InvalidArgumentException');
		new OneToOne('App\Models\Author', 'App\Models\Profile', TRUE, 'test', 'web', 'exception');
	}
	
	public function testMapped3()
	{
		$object = new OneToOne('App\Models\Profile', 'App\Models\Author', FALSE);
		$this->assertEquals('author', $object->getName());
		$this->assertEquals('author', $object->name);

		$this->assertEquals('App\Models\Profile', $object->getSourceEntity());
		$this->assertEquals('App\Models\Profile', $object->sourceEntity);
		$this->assertEquals('App\Models\Author', $object->getTargetEntity());
		$this->assertEquals('App\Models\Author', $object->targetEntity);
		
		$this->assertEquals('profiles', $object->getSourceTable());
		$this->assertEquals('profiles', $object->sourceTable);
		$this->assertEquals('authors', $object->getTargetTable());
		$this->assertEquals('authors', $object->targetTable);

		$this->assertEquals('author_id', $object->getSourceColumn());
		$this->assertEquals('author_id', $object->sourceColumn);
		$this->assertEquals('id', $object->getTargetColumn());
		$this->assertEquals('id', $object->targetColumn);
	}
	
	public function testMapped4()
	{
		$object = new OneToOne('App\Models\Profile', 'App\Models\Author', FALSE, 'test', 'name', 'title');
		$this->assertEquals('test', $object->getName());
		$this->assertEquals('test', $object->name);

		$this->assertEquals('App\Models\Profile', $object->getSourceEntity());
		$this->assertEquals('App\Models\Profile', $object->sourceEntity);
		$this->assertEquals('App\Models\Author', $object->getTargetEntity());
		$this->assertEquals('App\Models\Author', $object->targetEntity);
		
		$this->assertEquals('profiles', $object->getSourceTable());
		$this->assertEquals('profiles', $object->sourceTable);
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
		new OneToOne('App\Models\Article', 'App\Models\Author', FALSE, 'test', 'exception', 'title');
	}
	
	public function testGetData1()
	{
		$author = \ActiveMapper\Repository::factory('App\Models\Author')->find(1);
		$this->assertEquals(new \App\Models\Profile(array('author_id' => 1, 'web' => "http://www.example.com")), $author->profile());
	}
	
	public function testGetData2()
	{
		$profile = \dibi::select("*")->from('profiles')->where("[author_id] = %i", 1)->execute()
			->setRowClass('App\Models\Profile')->fetch();
		$this->assertEquals(new \App\Models\Author(array('id' => 1, 'name' => "František Vomáčka")), $profile->author());
	}
}
