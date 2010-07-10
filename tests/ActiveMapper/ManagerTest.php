<?php
namespace ActiveMapperTests;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\Manager,
	ActiveMapper\DibiRepository,
	ActiveMapper\DibiPersister,
	App\Models\Author,
	dibi;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\Manager */
	private $object;

	protected function setUp()
	{
		$this->object = new Manager(dibi::getConnection());
	}

	public function testGetRepository()
	{
		$this->assertEquals(new DibiRepository($this->object, 'App\Models\Author'), $this->object->getRepository('App\Models\Author'));
	}

	public function testSetRepository()
	{
		$data = new DibiRepository($this->object, 'App\Models\Author');
		$this->object->setRepository('App\Models\Author', $data);
		$this->assertSame($data, $this->object->getRepository('App\Models\Author'));
	}

	public function testGetPersister()
	{
		$this->assertEquals(new DibiPersister($this->object, 'App\Models\Author'), $this->object->getPersister('App\Models\Author'));
	}

	public function testSetPersister()
	{
		$data = new DibiPersister($this->object, 'App\Models\Author');
		$this->object->setPersister('App\Models\Author', $data);
		$this->assertSame($data, $this->object->getPersister('App\Models\Author'));
	}

	public function testGetIdenityMap()
	{
		$data = new \ActiveMapper\IdentityMap($this->object, 'App\Models\Author');
		$this->assertEquals($data, $this->object->getIdentityMap('App\Models\Author'));
	}

	public function testGetUnitOfWork()
	{
		$data = new \ActiveMapper\UnitOfWork($this->object);
		$this->assertEquals($data, $this->object->getUnitOfWork());
		$this->assertEquals($data, $this->object->unitOfWork);
	}

	public function testGetAssociationsMap()
	{
		$data = new \ActiveMapper\Associations\Map($this->object);
		$this->assertEquals($data, $this->object->getAssociationsMap());
		$this->assertEquals($data, $this->object->associationsMap);
	}

	public function testFind1()
	{
		$data = $this->object->find('App\Models\Author', 1);
		$this->assertEquals(1, $data->id);
		$this->assertType('App\Models\Author', $data);
   	}

	public function testFindBy1()
	{
		$data = $this->object->findByName('App\Models\Author', 'Patrik Votoček');
		$this->assertEquals(3, $data->id);
		$this->assertType('App\Models\Author', $data);
	}

	public function testFindBy2()
	{
		$data = $this->object->findById('App\Models\Author', 2);
		$this->assertEquals(2, $data->id);
		$this->assertType('App\Models\Author', $data);
	}

	public function testFindAll1()
	{
		$rows = $this->object->findAll('App\Models\Author');
		
		$this->markTestIncomplete();
		//$this->assertType('ActiveMapper\FluentCollection', $rows);

		$this->assertEquals(3, count($rows));
		$this->assertType('App\Models\Author', $rows[0]);
		$this->assertTrue(isset($rows[0]));
       	$this->assertEquals(1, $rows[0]->id);
	}

	// HOW TO SPLIT TESTS
	public function testPersistingTotal()
	{
		$author = new Author(array('name' => "Franta Skočdopole", 'web' => "http://example.com/"));
		// TEST INSERT
		$this->object->persist($author);
		$this->object->flush();
		$id = dibi::getInsertId();
		$this->assertEquals($id, $author->id);
		$this->assertEquals(
			array('id' => $id, 'name' => "Franta Skočdopole", 'web' => "http://example.com/"),
			(array)dibi::select("*")->from("authors")->where("[id] = %i", $id)->execute()->fetch()
		);
		// TEST UPDATE
		$author->name = "Franta Vomáčka";
		$this->object->persist($author);
		$this->object->flush();
		$this->assertEquals($id, $author->id);
		$this->assertEquals(
			array('id' => $id, 'name' => "Franta Vomáčka", 'web' => "http://example.com/"),
			(array)dibi::select("*")->from("authors")->where("[id] = %i", $id)->execute()->fetch()
		);
		// TEST DELETE
		$this->object->delete($author);
		$this->object->flush();
		$this->assertFalse(dibi::select("*")->from("authors")->where("[id] = %i", $id)->execute()->fetch());
	}

	public function testCallException1()
	{
		$this->setExpectedException("MemberAccessException");
		Manager::exception();
	}

	public function testCallException2()
	{
		$this->setExpectedException("MemberAccessException");
		$this->object->exception();
	}

	public function testGetInstance()
	{
		$data = new Manager(dibi::getConnection());
		$this->assertEquals($data, Manager::getManager());
		$this->assertEquals($data, Manager::getManager(dibi::getConnection()));
	}

	public function testPersistAssociationsOneToOne1()
	{
		$author = $this->object->find('App\Models\Author', 1);
		$blog = $author->blog;
		$this->assertEquals(1, $author->blog->id);
		$author->blog = NULL;
		$this->object->persist($author);
		$this->object->flush();
		$this->assertFalse(dibi::select("[id]")->from("blogs")->where("[author_id] = %i", 1)->execute()->fetchSingle());
		$this->assertNull($author->blog);
		$author->blog = $blog;
		$this->object->persist($author);
		$this->object->flush();
		$this->assertEquals(1, dibi::select("[id]")->from("blogs")->where("[author_id] = %i", 1)->execute()->fetchSingle());
		$this->assertEquals(1, $author->blog->id);
	}

	public function testPersistAssociationsOneToOne2()
	{
		$blog = $this->object->find('App\Models\Blog', 1);
		$author = $blog->author;
		$this->assertEquals(1, $blog->author->id);
		$blog->author = NULL;
		$this->object->persist($blog);
		$this->object->flush();
		$this->assertNull(dibi::select("[author_id]")->from("blogs")->where("[id] = %i", 1)->execute()->fetchSingle());
		$this->assertNull($blog->author);
		$blog->author = $author;
		$this->object->persist($blog);
		$this->object->flush();
		$this->assertEquals(1, dibi::select("[author_id]")->from("blogs")->where("[id] = %i", 1)->execute()->fetchSingle());
		$this->assertEquals(1, $blog->author->id);
	}

	public function testPersistAssociationsOneToMany()
	{
		$author = $this->object->find('App\Models\Author', 3);
		$applications = $author->applications;
		$this->assertEquals(2, count($author->applications));
		$this->assertEquals(array(5, 6), array_keys($author->applications));
		$author->applications = NULL;
		$this->object->persist($author);
		$this->object->flush();
		$this->assertFalse(dibi::select("[id]")->from("applications")->where("[author_id] = %i", 3)->execute()->fetchSingle());
		$this->assertNull($author->applications);
		$author->applications = $applications;
		$this->object->persist($author);
		$this->object->flush();
		$this->assertEquals(5, dibi::select("[id]")->from("applications")->where("[author_id] = %i", 3)->execute()->fetchSingle());
		$this->assertEquals(2, count($author->applications));
		$this->assertEquals(array(5, 6), array_keys($author->applications));
	}

	public function testPersistAssociationsManyToOne()
	{
		$application = $this->object->find('App\Models\Application', 1);
		$author = $application->author;
		$this->assertEquals(1, $application->author->id);
		$application->author = NULL;
		$this->object->persist($application);
		$this->object->flush();
		$this->assertNull(dibi::select("[author_id]")->from("applications")->where("[id] = %i", 1)->execute()->fetchSingle());
		$this->assertNull($application->author);
		$application->author = $author;
		$this->object->persist($application);
		$this->object->flush();
		$this->assertEquals(1, dibi::select("[author_id]")->from("applications")->where("[id] = %i", 1)->execute()->fetchSingle());
		$this->assertEquals(1, $application->author->id);
	}

	public function testPersistAssociationsManyToMany1()
	{
		$appliation = $this->object->find('App\Models\Application', 6);
		$tags = $appliation->tags;
		$this->assertEquals(3, count($appliation->tags));
		$this->assertEquals(array(1, 2, 3), array_keys($appliation->tags));
		$appliation->tags = NULL;
		$this->object->persist($appliation);
		$this->object->flush();
		$this->assertFalse(dibi::select("[tag_id]")->from("applications_tags")->where("[application_id] = %i", 6)->execute()->fetchSingle());
		$this->assertNull($appliation->tags);
		$appliation->tags = $tags;
		$this->object->persist($appliation);
		$this->object->flush();
		$this->assertEquals(1, dibi::select("[tag_id]")->from("applications_tags")->where("[application_id] = %i", 6)->execute()->fetchSingle());
		$this->assertEquals(3, count($appliation->tags));
		$this->assertEquals(array(1, 2, 3), array_keys($appliation->tags));
	}
	
	public function testPersistAssociationsManyToMany2()
	{
		$tag = $this->object->find('App\Models\Tag', 4);
		$applications = $tag->applications;
		$this->assertEquals(2, count($tag->applications));
		$this->assertEquals(array(2, 5), array_keys($tag->applications));
		$tag->applications = NULL;
		$this->object->persist($tag);
		$this->object->flush();
		$this->assertFalse(dibi::select("[application_id]")->from("applications_tags")->where("[tag_id] = %i", 4)->execute()->fetchSingle());
		$this->assertNull($tag->applications);
		$tag->applications = $applications;
		$this->object->persist($tag);
		$this->object->flush();
		$this->assertEquals(2, dibi::select("[application_id]")->from("applications_tags")->where("[tag_id] = %i", 4)->execute()->fetchSingle());
		$this->assertEquals(2, count($tag->applications));
		$this->assertEquals(array(2, 5), array_keys($tag->applications));
	}
}