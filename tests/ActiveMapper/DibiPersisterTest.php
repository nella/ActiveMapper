<?php
namespace ActiveMapperTests;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\DibiPersister,
	ActiveMapper\Manager,
	dibi;

class DibiPersisterTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\DibiPersister */
	private $object;
	/** @var ActiveMapper\Manager */
	private $manager;

	protected function setUp()
	{
		$this->manager = Manager::getManager();
		$this->object = new DibiPersister($this->manager, 'App\Models\Author');
	}

	// HOW TO SPLIT TESTS
	public function testTotal()
	{
		$this->object->insert(author(array('name' => "Franta Skočdopole", 'web' => "http://example.com/")));
		$id = dibi::getInsertId();
		$this->assertEquals(
			(array)dibi::select("*")->from("authors")->where("[id] = %i", $id)->execute()->fetch(),
			array('id' => $id, 'name' => "Franta Skočdopole", 'web' => "http://example.com/")
		);
		$this->assertEquals($id, $this->object->lastPrimaryKey());
		$author = author(array('id' => $id, 'name' => "Franta Vomáčka", 'web' => "http://example.com/"));
		$this->object->update($author);
		$this->assertEquals(
			(array)dibi::select("*")->from("authors")->where("[id] = %i", $id)->execute()->fetch(),
			array('id' => $id, 'name' => "Franta Vomáčka", 'web' => "http://example.com/")
		);
		$this->object->delete($author);
		$this->assertFalse(dibi::select("*")->from("authors")->where("[id] = %i", $id)->execute()->fetch());
	}

	public function testCallException1()
	{
		$this->setExpectedException("MemberAccessException");
		DibiPersister::exception();
	}

	public function testCallException2()
	{
		$this->setExpectedException("MemberAccessException");
		$this->object->exception();
	}
	
	public function testGetDibiPersister()
	{
		$data = new DibiPersister($this->manager, 'App\Models\Author');
		$this->assertEquals($data, DibiPersister::getDibiPersister($this->manager, 'App\Models\Author'));
	}
}