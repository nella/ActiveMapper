<?php
namespace ActiveMapperTests\Associations;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\Associations\ManyToMany;

class ManyToManyTest extends \PHPUnit_Framework_TestCase
{
	public function testMapped1()
	{
		$object = new ManyToMany('App\Models\Application', 'App\Models\Tag');
		$this->assertEquals('tags', $object->getName());
		$this->assertEquals('tags', $object->name);

		$this->assertEquals('App\Models\Application', $object->getSourceEntity());
		$this->assertEquals('App\Models\Application', $object->sourceEntity);
		$this->assertEquals('App\Models\Tag', $object->getTargetEntity());
		$this->assertEquals('App\Models\Tag', $object->targetEntity);
		
		$this->assertEquals('applications_tags', $object->getJoinTable());
		$this->assertEquals('applications_tags', $object->joinTable);

		$this->assertEquals('id', $object->getSourceColumn());
		$this->assertEquals('id', $object->sourceColumn);
		$this->assertEquals('id', $object->getTargetColumn());
		$this->assertEquals('id', $object->targetColumn);
		
		$this->assertEquals('application_id', $object->getJoinSourceColumn());
		$this->assertEquals('application_id', $object->joinSourceColumn);
		$this->assertEquals('tag_id', $object->getJoinTargetColumn());
		$this->assertEquals('tag_id', $object->joinTargetColumn);

		$this->assertTrue($object->isMapped());
		$this->assertTrue($object->getMapped());
		$this->assertTrue($object->mapped);
	}
	
	public function testMapped2()
	{
		$object = new ManyToMany('App\Models\Application', 'App\Models\Tag', TRUE, 'test', 'title', 'name', 'test_test',
			'tag_name', 'application_title');
		$this->assertEquals('test', $object->getName());
		$this->assertEquals('test', $object->name);

		$this->assertEquals('App\Models\Application', $object->getSourceEntity());
		$this->assertEquals('App\Models\Application', $object->sourceEntity);
		$this->assertEquals('App\Models\Tag', $object->getTargetEntity());
		$this->assertEquals('App\Models\Tag', $object->targetEntity);

		$this->assertEquals('title', $object->getSourceColumn());
		$this->assertEquals('title', $object->sourceColumn);
		$this->assertEquals('name', $object->getTargetColumn());
		$this->assertEquals('name', $object->targetColumn);
		
		$this->assertEquals('application_title', $object->getJoinSourceColumn());
		$this->assertEquals('application_title', $object->joinSourceColumn);
		$this->assertEquals('tag_name', $object->getJoinTargetColumn());
		$this->assertEquals('tag_name', $object->joinTargetColumn);

		$this->assertTrue($object->isMapped());
		$this->assertTrue($object->getMapped());
		$this->assertTrue($object->mapped);
	}
	
	public function testMapped3()
	{
		$object = new ManyToMany('App\Models\Tag', 'App\Models\Application', FALSE);
		$this->assertEquals('applications', $object->getName());
		$this->assertEquals('applications', $object->name);

		$this->assertEquals('App\Models\Tag', $object->getSourceEntity());
		$this->assertEquals('App\Models\Tag', $object->sourceEntity);
		$this->assertEquals('App\Models\Application', $object->getTargetEntity());
		$this->assertEquals('App\Models\Application', $object->targetEntity);
		
		$this->assertEquals('applications_tags', $object->getJoinTable());
		$this->assertEquals('applications_tags', $object->joinTable);

		$this->assertEquals('id', $object->getSourceColumn());
		$this->assertEquals('id', $object->sourceColumn);
		$this->assertEquals('id', $object->getTargetColumn());
		$this->assertEquals('id', $object->targetColumn);
		
		$this->assertEquals('tag_id', $object->getJoinSourceColumn());
		$this->assertEquals('tag_id', $object->joinSourceColumn);
		$this->assertEquals('application_id', $object->getJoinTargetColumn());
		$this->assertEquals('application_id', $object->joinTargetColumn);

		$this->assertFalse($object->isMapped());
		$this->assertFalse($object->getMapped());
		$this->assertFalse($object->mapped);
	}
	
	public function testMapped4()
	{
		$object = new ManyToMany('App\Models\Tag', 'App\Models\Application', FALSE, 'test', 'name', 'title', 'test_test');
		$this->assertEquals('test', $object->getName());
		$this->assertEquals('test', $object->name);

		$this->assertEquals('App\Models\Tag', $object->getSourceEntity());
		$this->assertEquals('App\Models\Tag', $object->sourceEntity);
		$this->assertEquals('App\Models\Application', $object->getTargetEntity());
		$this->assertEquals('App\Models\Application', $object->targetEntity);
		
		$this->assertEquals('test_test', $object->getJoinTable());
		$this->assertEquals('test_test', $object->joinTable);

		$this->assertEquals('name', $object->getSourceColumn());
		$this->assertEquals('name', $object->sourceColumn);
		$this->assertEquals('title', $object->getTargetColumn());
		$this->assertEquals('title', $object->targetColumn);
		
		$this->assertEquals('tag_name', $object->getJoinSourceColumn());
		$this->assertEquals('tag_name', $object->joinSourceColumn);
		$this->assertEquals('application_title', $object->getJoinTargetColumn());
		$this->assertEquals('application_title', $object->joinTargetColumn);

		$this->assertFalse($object->isMapped());
		$this->assertFalse($object->getMapped());
		$this->assertFalse($object->mapped);
	}
}