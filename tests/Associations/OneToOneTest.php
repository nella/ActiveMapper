<?php
namespace ActiveMapperTests\Associations;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\Associations\OneToOne;

class OneToOneTest extends \PHPUnit_Framework_TestCase
{
	public function testMapped1()
	{
		$object = new OneToOne('App\Models\Author', 'App\Models\Blog');
		$this->assertEquals('blog', $object->getName());
		$this->assertEquals('blog', $object->name);

		$this->assertEquals('App\Models\Author', $object->getSourceEntity());
		$this->assertEquals('App\Models\Author', $object->sourceEntity);
		$this->assertEquals('App\Models\Blog', $object->getTargetEntity());
		$this->assertEquals('App\Models\Blog', $object->targetEntity);

		$this->assertEquals('id', $object->getSourceColumn());
		$this->assertEquals('id', $object->sourceColumn);
		$this->assertEquals('author_id', $object->getTargetColumn());
		$this->assertEquals('author_id', $object->targetColumn);

		$this->assertTrue($object->isMapped());
		$this->assertTrue($object->getMapped());
		$this->assertTrue($object->mapped);
	}
	
	public function testMapped2()
	{
		$object = new OneToOne('App\Models\Author', 'App\Models\Blog', TRUE, 'test', 'url', 'name');
		$this->assertEquals('test', $object->getName());
		$this->assertEquals('test', $object->name);

		$this->assertEquals('App\Models\Author', $object->getSourceEntity());
		$this->assertEquals('App\Models\Author', $object->sourceEntity);
		$this->assertEquals('App\Models\Blog', $object->getTargetEntity());
		$this->assertEquals('App\Models\Blog', $object->targetEntity);

		$this->assertEquals('name', $object->getSourceColumn());
		$this->assertEquals('name', $object->sourceColumn);
		$this->assertEquals('url', $object->getTargetColumn());
		$this->assertEquals('url', $object->targetColumn);

		$this->assertTrue($object->isMapped());
		$this->assertTrue($object->getMapped());
		$this->assertTrue($object->mapped);
	}
	
	public function testMapped3()
	{
		$object = new OneToOne('App\Models\Blog', 'App\Models\Author', FALSE);
		$this->assertEquals('author', $object->getName());
		$this->assertEquals('author', $object->name);

		$this->assertEquals('App\Models\Blog', $object->getSourceEntity());
		$this->assertEquals('App\Models\Blog', $object->sourceEntity);
		$this->assertEquals('App\Models\Author', $object->getTargetEntity());
		$this->assertEquals('App\Models\Author', $object->targetEntity);

		$this->assertEquals('author_id', $object->getSourceColumn());
		$this->assertEquals('author_id', $object->sourceColumn);
		$this->assertEquals('id', $object->getTargetColumn());
		$this->assertEquals('id', $object->targetColumn);

		$this->assertFalse($object->isMapped());
		$this->assertFalse($object->getMapped());
		$this->assertFalse($object->mapped);
	}
	
	public function testMapped4()
	{
		$object = new OneToOne('App\Models\Blog', 'App\Models\Author', FALSE, 'test', 'name', 'title');
		$this->assertEquals('test', $object->getName());
		$this->assertEquals('test', $object->name);

		$this->assertEquals('App\Models\Blog', $object->getSourceEntity());
		$this->assertEquals('App\Models\Blog', $object->sourceEntity);
		$this->assertEquals('App\Models\Author', $object->getTargetEntity());
		$this->assertEquals('App\Models\Author', $object->targetEntity);

		$this->assertEquals('title', $object->getSourceColumn());
		$this->assertEquals('title', $object->sourceColumn);
		$this->assertEquals('name', $object->getTargetColumn());
		$this->assertEquals('name', $object->targetColumn);

		$this->assertFalse($object->isMapped());
		$this->assertFalse($object->getMapped());
		$this->assertFalse($object->mapped);
	}
}
