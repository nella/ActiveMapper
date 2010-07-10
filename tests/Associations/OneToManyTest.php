<?php
namespace ActiveMapperTests\Associations;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\Associations\OneToMany;

class OneToManyTest extends \PHPUnit_Framework_TestCase
{
	public function test1()
	{
		$object = new OneToMany('App\Models\Author', 'App\Models\Application');
		$this->assertEquals('applications', $object->getName());
		$this->assertEquals('applications', $object->name);

		$this->assertEquals('App\Models\Author', $object->getSourceEntity());
		$this->assertEquals('App\Models\Author', $object->sourceEntity);
		$this->assertEquals('App\Models\Application', $object->getTargetEntity());
		$this->assertEquals('App\Models\Application', $object->targetEntity);

		$this->assertEquals('id', $object->getSourceColumn());
		$this->assertEquals('id', $object->sourceColumn);
		$this->assertEquals('author_id', $object->getTargetColumn());
		$this->assertEquals('author_id', $object->targetColumn);
	}
	
	public function test2()
	{
		$object = new OneToMany('App\Models\Author', 'App\Models\Application', 'test', 'title');
		$this->assertEquals('test', $object->getName());
		$this->assertEquals('test', $object->name);

		$this->assertEquals('App\Models\Author', $object->getSourceEntity());
		$this->assertEquals('App\Models\Author', $object->sourceEntity);
		$this->assertEquals('App\Models\Application', $object->getTargetEntity());
		$this->assertEquals('App\Models\Application', $object->targetEntity);

		$this->assertEquals('id', $object->getSourceColumn());
		$this->assertEquals('id', $object->sourceColumn);
		$this->assertEquals('title', $object->getTargetColumn());
		$this->assertEquals('title', $object->targetColumn);
	}
}
