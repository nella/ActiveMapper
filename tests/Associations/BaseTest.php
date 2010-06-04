<?php
namespace ActiveMapperTests\Associations;

require_once __DIR__ . "/../bootstrap.php";
require_once "PHPUnit/Framework.php";

class MyAssociation extends \ActiveMapper\Associations\Base { }

class MyAssociationTest extends \PHPUnit_Framework_TestCase
{
	public function testMapped1()
	{
		$object = new MyAssociation('App\Models\Author', 'App\Models\Article');

		$this->assertEquals('App\Models\Author', $object->getSourceEntity());
		$this->assertEquals('App\Models\Author', $object->sourceEntity);
		$this->assertEquals('App\Models\Article', $object->getTargetEntity());
		$this->assertEquals('App\Models\Article', $object->targetEntity);

		$this->assertEquals('authors', $object->getSourceTable());
		$this->assertEquals('authors', $object->sourceTable);
		$this->assertEquals('articles', $object->getTargetTable());
		$this->assertEquals('articles', $object->targetTable);
	}
}