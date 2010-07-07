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

		$this->assertEquals('title', $object->getSourceColumn());
		$this->assertEquals('title', $object->sourceColumn);
		$this->assertEquals('name', $object->getTargetColumn());
		$this->assertEquals('name', $object->targetColumn);
	}
}