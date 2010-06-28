<?php
namespace ActiveMapperTests\Associations;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\Associations\OneToMany;

class OneToManyTest extends \PHPUnit_Framework_TestCase
{
	public function testMapped1()
	{
		$object = new OneToMany('App\Models\Author', 'App\Models\Application');
		$this->assertEquals('applications', $object->getName());
		$this->assertEquals('applications', $object->name);

		$this->assertEquals('App\Models\Author', $object->getSourceEntity());
		$this->assertEquals('App\Models\Author', $object->sourceEntity);
		$this->assertEquals('App\Models\Application', $object->getTargetEntity());
		$this->assertEquals('App\Models\Application', $object->targetEntity);
		
		$this->assertEquals('authors', $object->getSourceTable());
		$this->assertEquals('authors', $object->sourceTable);
		$this->assertEquals('applications', $object->getTargetTable());
		$this->assertEquals('applications', $object->targetTable);

		$this->assertEquals('id', $object->getSourceColumn());
		$this->assertEquals('id', $object->sourceColumn);
		$this->assertEquals('author_id', $object->getTargetColumn());
		$this->assertEquals('author_id', $object->targetColumn);
	}
	
	public function testMapped2()
	{
		$object = new OneToMany('App\Models\Author', 'App\Models\Application', 'test', 'title', 'name');
		$this->assertEquals('test', $object->getName());
		$this->assertEquals('test', $object->name);

		$this->assertEquals('App\Models\Author', $object->getSourceEntity());
		$this->assertEquals('App\Models\Author', $object->sourceEntity);
		$this->assertEquals('App\Models\Application', $object->getTargetEntity());
		$this->assertEquals('App\Models\Application', $object->targetEntity);
		
		$this->assertEquals('authors', $object->getSourceTable());
		$this->assertEquals('authors', $object->sourceTable);
		$this->assertEquals('applications', $object->getTargetTable());
		$this->assertEquals('applications', $object->targetTable);

		$this->assertEquals('name', $object->getSourceColumn());
		$this->assertEquals('name', $object->sourceColumn);
		$this->assertEquals('title', $object->getTargetColumn());
		$this->assertEquals('title', $object->targetColumn);
	}
	
	public function testNotExistSourceColumnException()
	{
		$this->setExpectedException('InvalidArgumentException');
		new OneToMany('App\Models\Author', 'App\Models\Application', 'test', 'title', 'exception');
	}
	
	public function testGetData1()
	{
		$author = \ActiveMapper\Repository::factory('App\Models\Author')->find(3);
		$applications = $author->applications()->fetchAll(NULL, 1);
		$this->assertEquals(new \App\Models\Application(array(
			'id' => 5,
			'title' => "Nella",
			'web' => "http://nellacms.com/",
			'slogan' => "Tiny & simple CMS based Nette Framework",
			'author_id' => 3,
		)), $applications[0]);
	}
}
