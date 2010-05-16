<?php
namespace App\Models;

require_once __DIR__ . "/../bootstrap.php";
require_once "PHPUnit/Framework.php";

class ManyToManyTest extends \PHPUnit_Framework_TestCase
{
	public function testMapped1()
	{
		$object = new \ActiveMapper\Associations\ManyToMany('App\Models\Article', 'App\Models\Tag');
		$this->assertEquals('tags', $object->getName());
		$this->assertEquals('tags', $object->name);

		$this->assertEquals('App\Models\Article', $object->getSourceEntity());
		$this->assertEquals('App\Models\Article', $object->sourceEntity);
		$this->assertEquals('App\Models\Tag', $object->getTargetEntity());
		$this->assertEquals('App\Models\Tag', $object->targetEntity);
		
		$this->assertEquals('articles', $object->getSourceTable());
		$this->assertEquals('articles', $object->sourceTable);
		$this->assertEquals('tags', $object->getTargetTable());
		$this->assertEquals('tags', $object->targetTable);
		
		$this->assertEquals('articles_tags', $object->getJoinTable());
		$this->assertEquals('articles_tags', $object->joinTable);

		$this->assertEquals('id', $object->getSourceColumn());
		$this->assertEquals('id', $object->sourceColumn);
		$this->assertEquals('id', $object->getTargetColumn());
		$this->assertEquals('id', $object->targetColumn);
		
		$this->assertEquals('article_id', $object->getJoinTableSourceColumn());
		$this->assertEquals('article_id', $object->joinTableSourceColumn);
		$this->assertEquals('tag_id', $object->getJoinTableTargetColumn());
		$this->assertEquals('tag_id', $object->joinTableTargetColumn);
	}
	
	public function testMapped2()
	{
		$object = new \ActiveMapper\Associations\ManyToMany(
			'App\Models\Article', 'App\Models\Tag', TRUE, 'test', 'title', 'name', 'test_test'
		);
		$this->assertEquals('test', $object->getName());
		$this->assertEquals('test', $object->name);

		$this->assertEquals('App\Models\Article', $object->getSourceEntity());
		$this->assertEquals('App\Models\Article', $object->sourceEntity);
		$this->assertEquals('App\Models\Tag', $object->getTargetEntity());
		$this->assertEquals('App\Models\Tag', $object->targetEntity);
		
		$this->assertEquals('articles', $object->getSourceTable());
		$this->assertEquals('articles', $object->sourceTable);
		$this->assertEquals('tags', $object->getTargetTable());
		$this->assertEquals('tags', $object->targetTable);

		$this->assertEquals('title', $object->getSourceColumn());
		$this->assertEquals('title', $object->sourceColumn);
		$this->assertEquals('name', $object->getTargetColumn());
		$this->assertEquals('name', $object->targetColumn);
		
		$this->assertEquals('article_title', $object->getJoinTableSourceColumn());
		$this->assertEquals('article_title', $object->joinTableSourceColumn);
		$this->assertEquals('tag_name', $object->getJoinTableTargetColumn());
		$this->assertEquals('tag_name', $object->joinTableTargetColumn);
	}
	
	public function testNotExistSourceColumnException()
	{
		$this->setExpectedException('InvalidArgumentException');
		new \ActiveMapper\Associations\OneToOne('App\Models\Article', 'App\Models\Tag', TRUE, 'test', 'title', 'exception');
	}
	
	public function testMapped3()
	{
		$object = new \ActiveMapper\Associations\ManyToMany('App\Models\Tag', 'App\Models\Article', FALSE);
		$this->assertEquals('articles', $object->getName());
		$this->assertEquals('articles', $object->name);

		$this->assertEquals('App\Models\Tag', $object->getSourceEntity());
		$this->assertEquals('App\Models\Tag', $object->sourceEntity);
		$this->assertEquals('App\Models\Article', $object->getTargetEntity());
		$this->assertEquals('App\Models\Article', $object->targetEntity);
		
		$this->assertEquals('tags', $object->getSourceTable());
		$this->assertEquals('tags', $object->sourceTable);
		$this->assertEquals('articles', $object->getTargetTable());
		$this->assertEquals('articles', $object->targetTable);
		
		$this->assertEquals('articles_tags', $object->getJoinTable());
		$this->assertEquals('articles_tags', $object->joinTable);

		$this->assertEquals('id', $object->getSourceColumn());
		$this->assertEquals('id', $object->sourceColumn);
		$this->assertEquals('id', $object->getTargetColumn());
		$this->assertEquals('id', $object->targetColumn);
		
		$this->assertEquals('tag_id', $object->getJoinTableSourceColumn());
		$this->assertEquals('tag_id', $object->joinTableSourceColumn);
		$this->assertEquals('article_id', $object->getJoinTableTargetColumn());
		$this->assertEquals('article_id', $object->joinTableTargetColumn);
	}
	
	public function testMapped4()
	{
		$object = new \ActiveMapper\Associations\ManyToMany(
			'App\Models\Tag', 'App\Models\Article', FALSE, 'test', 'name', 'title', 'test_test'
		);
		$this->assertEquals('test', $object->getName());
		$this->assertEquals('test', $object->name);

		$this->assertEquals('App\Models\Tag', $object->getSourceEntity());
		$this->assertEquals('App\Models\Tag', $object->sourceEntity);
		$this->assertEquals('App\Models\Article', $object->getTargetEntity());
		$this->assertEquals('App\Models\Article', $object->targetEntity);
		
		$this->assertEquals('tags', $object->getSourceTable());
		$this->assertEquals('tags', $object->sourceTable);
		$this->assertEquals('articles', $object->getTargetTable());
		$this->assertEquals('articles', $object->targetTable);
		
		$this->assertEquals('test_test', $object->getJoinTable());
		$this->assertEquals('test_test', $object->joinTable);

		$this->assertEquals('name', $object->getSourceColumn());
		$this->assertEquals('name', $object->sourceColumn);
		$this->assertEquals('title', $object->getTargetColumn());
		$this->assertEquals('title', $object->targetColumn);
		
		$this->assertEquals('tag_name', $object->getJoinTableSourceColumn());
		$this->assertEquals('tag_name', $object->joinTableSourceColumn);
		$this->assertEquals('article_title', $object->getJoinTableTargetColumn());
		$this->assertEquals('article_title', $object->joinTableTargetColumn);
	}
	
	public function testNotExistTargetColumnException()
	{
		$this->setExpectedException('InvalidArgumentException');
		new \ActiveMapper\Associations\OneToOne('App\Models\Tag', 'App\Models\Article', FALSE, 'test', 'exception', 'title');
	}
	
	public function testGetData1()
	{
		$article = \ActiveMapper\Repository::find('App\Models\Article', 1);
		$tags = $article->tags();
		$this->assertType('ActiveMapper\RepositoryCollection', $tags);
		$this->assertEquals(new \App\Models\Tag(array('id' => 18, 'name' => "Late Static Binding")), $tags[0]);
		$this->assertEquals(new \App\Models\Tag(array('id' => 17, 'name' => "Singleton")), $tags[1]);
		$this->assertEquals(new \App\Models\Tag(array('id' => 1, 'name' => "PHP")), $tags[2]);
	}
	
	public function testGetData2()
	{
		$tag = \ActiveMapper\Repository::find('App\Models\Tag', 18);
		$articles = $tag->articles();
		$this->assertType('ActiveMapper\RepositoryCollection', $articles);
		$this->assertEquals(new \App\Models\Article(array(
			'id' => 1, 
			'title' => "Rodičovské problémy Táty Singletonovského objektu", 
			'content' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed scelerisque, ligula ac rhoncus consequat,"
				." quam eros bibendum nunc, vitae egestas sem nulla vel enim. Ut id euismod velit. Nullam nec faucibus arcu. Maecenas"
				." faucibus pulvinar venenatis. Mauris commodo adipiscing lectus sit amet viverra. Aliquam porta nisi vitae eros tempus"
				." non fermentum augue varius. Cras libero diam, condimentum quis rutrum quis, imperdiet nec mi. Quisque laoreet, augue eu"
				." venenatis tristique, tellus lectus fermentum nisl, at pretium purus augue at risus. Nulla sed fermentum ligula."
				." Nulla ut libero velit, eu dignissim dui. Morbi lacus mi, bibendum vitae sodales vitae, blandit eu tellus. Morbi"
				." imperdiet semper quam, vitae tempor nulla fermentum rhoncus. Praesent ultricies imperdiet leo, nec facilisis ligula"
				." ornare in. Phasellus a sagittis nulla. Aenean hendrerit arcu leo, a elementum justo. Sed ipsum lacus, ullamcorper et"
				." mattis sit amet, luctus vulputate arcu. Suspendisse ut ligula non turpis pellentesque aliquam quis eu ante. ", 
			'author_id' => 3, 
			'create' => "2009-12-01", 
			'price' => 4.3, 
			'public' => TRUE
			
		)), $articles[0]);
	}
}