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
		
		$this->assertEquals('authors', $object->getSourceTable());
		$this->assertEquals('authors', $object->sourceTable);
		$this->assertEquals('blogs', $object->getTargetTable());
		$this->assertEquals('blogs', $object->targetTable);

		$this->assertEquals('id', $object->getSourceColumn());
		$this->assertEquals('id', $object->sourceColumn);
		$this->assertEquals('author_id', $object->getTargetColumn());
		$this->assertEquals('author_id', $object->targetColumn);
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
		
		$this->assertEquals('authors', $object->getSourceTable());
		$this->assertEquals('authors', $object->sourceTable);
		$this->assertEquals('blogs', $object->getTargetTable());
		$this->assertEquals('blogs', $object->targetTable);

		$this->assertEquals('name', $object->getSourceColumn());
		$this->assertEquals('name', $object->sourceColumn);
		$this->assertEquals('url', $object->getTargetColumn());
		$this->assertEquals('url', $object->targetColumn);
	}
	
	public function testNotExistSourceColumnException()
	{
		$this->setExpectedException('InvalidArgumentException');
		new OneToOne('App\Models\Author', 'App\Models\Blog', TRUE, 'test', 'web', 'exception');
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
		
		$this->assertEquals('blogs', $object->getSourceTable());
		$this->assertEquals('blogs', $object->sourceTable);
		$this->assertEquals('authors', $object->getTargetTable());
		$this->assertEquals('authors', $object->targetTable);

		$this->assertEquals('author_id', $object->getSourceColumn());
		$this->assertEquals('author_id', $object->sourceColumn);
		$this->assertEquals('id', $object->getTargetColumn());
		$this->assertEquals('id', $object->targetColumn);
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
		
		$this->assertEquals('blogs', $object->getSourceTable());
		$this->assertEquals('blogs', $object->sourceTable);
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
		$this->assertEquals(new \App\Models\Blog(array('id' => 1, 'author_id' => 1, 'name' => "PHP triky", 'url' => "http://php.vrana.cz")), $author->blog());
	}
	
	public function testGetData2()
	{
		$blog = \dibi::select("*")->from('blogs')->where("[author_id] = %i", 1)->execute()
			->setRowClass('App\Models\Blog')->fetch();
		$this->assertEquals(new \App\Models\Author(array('id' => 1, 'name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/")), $blog->author());
	}
}
