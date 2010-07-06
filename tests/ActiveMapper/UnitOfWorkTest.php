<?php
namespace ActiveMapperTests;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\Manager,
	ActiveMapper\UnitOfWork,
	App\Models\Author,
	dibi;

class UnitOfWorkTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\UnitOfWork */
	private $object;
	/** @var ActiveMapper\Manager */
	private $manager;

	protected function setUp()
	{
		$this->manager = Manager::getManager();
		$this->object = new UnitOfWork($this->manager);
	}

	// HOW TO SPLIT TESTS
	public function testTotal()
	{
		$author = new Author(array('name' => "Franta Skočdopole", 'web' => "http://example.com/"));
		// TEST INSERT
		$this->object->registerSave($author);
		$this->assertEquals(1, $this->object->count());
		$this->assertEquals(1, $this->object->count);
		$this->assertEquals(1, $this->object->getCount());
		$this->object->commit();
		$this->assertEquals(0, $this->object->count());
		$this->assertEquals(0, $this->object->count);
		$this->assertEquals(0, $this->object->getCount());
		$id = dibi::getInsertId();
		$this->assertEquals($id, $author->id);
		$this->assertEquals(
			array('id' => $id, 'name' => "Franta Skočdopole", 'web' => "http://example.com/"),
			(array)dibi::select("*")->from("authors")->where("[id] = %i", $id)->execute()->fetch()
		);
		// TEST UPDATE
		$author->name = "Franta Vomáčka";
		$this->object->registerSave($author);
		$this->assertEquals(1, $this->object->count());
		$this->assertEquals(1, $this->object->count);
		$this->assertEquals(1, $this->object->getCount());
		$this->object->commit();
		$this->assertEquals(0, $this->object->count());
		$this->assertEquals(0, $this->object->count);
		$this->assertEquals(0, $this->object->getCount());
		$this->assertEquals($id, $author->id);
		$this->assertEquals(
			array('id' => $id, 'name' => "Franta Vomáčka", 'web' => "http://example.com/"),
			(array)dibi::select("*")->from("authors")->where("[id] = %i", $id)->execute()->fetch()
		);
		// TEST DELETE
		$this->object->registerDelete($author);
		$this->assertEquals(1, $this->object->count());
		$this->assertEquals(1, $this->object->count);
		$this->assertEquals(1, $this->object->getCount());
		$this->object->commit();
		$this->assertEquals(0, $this->object->count());
		$this->assertEquals(0, $this->object->count);
		$this->assertEquals(0, $this->object->getCount());
		$this->assertFalse(dibi::select("*")->from("authors")->where("[id] = %i", $id)->execute()->fetch());
	}

	public function testCommitException()
	{
		$author = new Author(array('id' => 1, 'name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/"));
		$this->object->registerSave($author);
		$this->object->registerSave($author);
		$this->setExpectedException('InvalidStateException');
		$this->object->commit();
	}

	public function testGetUnitOfWork()
	{
		$data = new UnitOfWork($this->manager);
		$this->assertEquals($data, UnitOfWork::getUnitOfWork($this->manager));
	}
}