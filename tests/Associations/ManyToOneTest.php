<?php
namespace ActiveMapperTests\Associations;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\Associations\ManyToOne;

class ManyToOneTest extends \PHPUnit_Framework_TestCase
{
	public function testMapped1()
	{
		$object = new ManyToOne('App\Models\Application', 'App\Models\Author');
		$this->assertEquals('author', $object->getName());
		$this->assertEquals('author', $object->name);

		$this->assertEquals('App\Models\Application', $object->getSourceEntity());
		$this->assertEquals('App\Models\Application', $object->sourceEntity);
		$this->assertEquals('App\Models\Author', $object->getTargetEntity());
		$this->assertEquals('App\Models\Author', $object->targetEntity);
		
		$this->assertEquals('applications', $object->getSourceTable());
		$this->assertEquals('applications', $object->sourceTable);
		$this->assertEquals('authors', $object->getTargetTable());
		$this->assertEquals('authors', $object->targetTable);

		$this->assertEquals('author_id', $object->getSourceColumn());
		$this->assertEquals('author_id', $object->sourceColumn);
		$this->assertEquals('id', $object->getTargetColumn());
		$this->assertEquals('id', $object->targetColumn);
	}
	
	public function testMapped2()
	{
		$object = new ManyToOne('App\Models\Application', 'App\Models\Author', 'test', 'name', 'title');
		$this->assertEquals('test', $object->getName());
		$this->assertEquals('test', $object->name);

		$this->assertEquals('App\Models\Application', $object->getSourceEntity());
		$this->assertEquals('App\Models\Application', $object->sourceEntity);
		$this->assertEquals('App\Models\Author', $object->getTargetEntity());
		$this->assertEquals('App\Models\Author', $object->targetEntity);
		
		$this->assertEquals('applications', $object->getSourceTable());
		$this->assertEquals('applications', $object->sourceTable);
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
		new ManyToOne('App\Models\Application', 'App\Models\Author', 'test', 'exception', 'title');
	}
	
	public function testGetData1()
	{
		$application = \ActiveMapper\Repository::factory('App\Models\Application')->find(6);
		$this->assertEquals(new \App\Models\Author(array('id' => 3, 'name' => "Patrik Votoček", 'web' => "http://patrik.votocek.cz/")), $application->author());
	}
}