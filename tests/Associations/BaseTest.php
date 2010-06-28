<?php
namespace ActiveMapperTests\Associations;

require_once __DIR__ . "/../bootstrap.php";

class MyAssociation extends \ActiveMapper\Associations\Base { }

class MyAssociationTest extends \PHPUnit_Framework_TestCase
{
	public function testMapped1()
	{
		$object = new MyAssociation('App\Models\Author', 'App\Models\Application');

		$this->assertEquals('App\Models\Author', $object->getSourceEntity());
		$this->assertEquals('App\Models\Author', $object->sourceEntity);
		$this->assertEquals('App\Models\Application', $object->getTargetEntity());
		$this->assertEquals('App\Models\Application', $object->targetEntity);

		$this->assertEquals('authors', $object->getSourceTable());
		$this->assertEquals('authors', $object->sourceTable);
		$this->assertEquals('applications', $object->getTargetTable());
		$this->assertEquals('applications', $object->targetTable);
	}
}