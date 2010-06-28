<?php
namespace ActiveMapperTests\Associations;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\Associations\ManyToMany,
	ActiveMapper\Repository,
	App\Models\Tag;

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
		
		$this->assertEquals('applications', $object->getSourceTable());
		$this->assertEquals('applications', $object->sourceTable);
		$this->assertEquals('tags', $object->getTargetTable());
		$this->assertEquals('tags', $object->targetTable);
		
		$this->assertEquals('applications_tags', $object->getJoinTable());
		$this->assertEquals('applications_tags', $object->joinTable);

		$this->assertEquals('id', $object->getSourceColumn());
		$this->assertEquals('id', $object->sourceColumn);
		$this->assertEquals('id', $object->getTargetColumn());
		$this->assertEquals('id', $object->targetColumn);
		
		$this->assertEquals('application_id', $object->getJoinTableSourceColumn());
		$this->assertEquals('application_id', $object->joinTableSourceColumn);
		$this->assertEquals('tag_id', $object->getJoinTableTargetColumn());
		$this->assertEquals('tag_id', $object->joinTableTargetColumn);
	}
	
	public function testMapped2()
	{
		$object = new ManyToMany('App\Models\Application', 'App\Models\Tag', TRUE, 'test', 'title', 'name', 'test_test');
		$this->assertEquals('test', $object->getName());
		$this->assertEquals('test', $object->name);

		$this->assertEquals('App\Models\Application', $object->getSourceEntity());
		$this->assertEquals('App\Models\Application', $object->sourceEntity);
		$this->assertEquals('App\Models\Tag', $object->getTargetEntity());
		$this->assertEquals('App\Models\Tag', $object->targetEntity);
		
		$this->assertEquals('applications', $object->getSourceTable());
		$this->assertEquals('applications', $object->sourceTable);
		$this->assertEquals('tags', $object->getTargetTable());
		$this->assertEquals('tags', $object->targetTable);

		$this->assertEquals('title', $object->getSourceColumn());
		$this->assertEquals('title', $object->sourceColumn);
		$this->assertEquals('name', $object->getTargetColumn());
		$this->assertEquals('name', $object->targetColumn);
		
		$this->assertEquals('application_title', $object->getJoinTableSourceColumn());
		$this->assertEquals('application_title', $object->joinTableSourceColumn);
		$this->assertEquals('tag_name', $object->getJoinTableTargetColumn());
		$this->assertEquals('tag_name', $object->joinTableTargetColumn);
	}
	
	public function testNotExistSourceColumnException()
	{
		$this->setExpectedException('InvalidArgumentException');
		new ManyToMany('App\Models\Application', 'App\Models\Tag', TRUE, 'test', 'title', 'exception');
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
		
		$this->assertEquals('tags', $object->getSourceTable());
		$this->assertEquals('tags', $object->sourceTable);
		$this->assertEquals('applications', $object->getTargetTable());
		$this->assertEquals('applications', $object->targetTable);
		
		$this->assertEquals('applications_tags', $object->getJoinTable());
		$this->assertEquals('applications_tags', $object->joinTable);

		$this->assertEquals('id', $object->getSourceColumn());
		$this->assertEquals('id', $object->sourceColumn);
		$this->assertEquals('id', $object->getTargetColumn());
		$this->assertEquals('id', $object->targetColumn);
		
		$this->assertEquals('tag_id', $object->getJoinTableSourceColumn());
		$this->assertEquals('tag_id', $object->joinTableSourceColumn);
		$this->assertEquals('application_id', $object->getJoinTableTargetColumn());
		$this->assertEquals('application_id', $object->joinTableTargetColumn);
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
		
		$this->assertEquals('tags', $object->getSourceTable());
		$this->assertEquals('tags', $object->sourceTable);
		$this->assertEquals('applications', $object->getTargetTable());
		$this->assertEquals('applications', $object->targetTable);
		
		$this->assertEquals('test_test', $object->getJoinTable());
		$this->assertEquals('test_test', $object->joinTable);

		$this->assertEquals('name', $object->getSourceColumn());
		$this->assertEquals('name', $object->sourceColumn);
		$this->assertEquals('title', $object->getTargetColumn());
		$this->assertEquals('title', $object->targetColumn);
		
		$this->assertEquals('tag_name', $object->getJoinTableSourceColumn());
		$this->assertEquals('tag_name', $object->joinTableSourceColumn);
		$this->assertEquals('application_title', $object->getJoinTableTargetColumn());
		$this->assertEquals('application_title', $object->joinTableTargetColumn);
	}
	
	public function testNotExistTargetColumnException()
	{
		$this->setExpectedException('InvalidArgumentException');
		new ManyToMany('App\Models\Tag', 'App\Models\Application', FALSE, 'test', 'exception', 'title');
	}
	
	public function testGetData1()
	{
		$this->markTestSkipped('loading');
		$article = Repository::factory('App\Models\Application')->find(1);
		
		$tags = $article->tags();
		$this->assertType('ActiveMapper\RepositoryCollection', $tags);
		$this->assertEquals(new Tag(array('id' => 1, 'name' => "PHP")), $tags[0]);
		$this->assertEquals(new Tag(array('id' => 2, 'name' => "MySQL")), $tags[1]);
	}
	
	public function testGetData2()
	{
		$tag = Repository::factory('App\Models\Tag')->find(3);
		$applications = $tag->applications();
		$this->assertType('ActiveMapper\RepositoryCollection', $applications);
		$this->assertEquals(new \App\Models\Application(array(
			'id' => 6,
			'title' => "ActiveMapper",
			'web' => "http://addons.nette.org/cs/active-mapper",
			'slogan' => "Another dibi ORM",
			'author_id' => 3,
		)), $applications[0]);
	}
}