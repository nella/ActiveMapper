<?php
namespace ActiveMapperTests;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\Manager,
	ActiveMapper\UnitOfWork,
	dibi,
	\Nette\Reflection\PropertyReflection;

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
		$author = author(array('name' => "Franta Skočdopole", 'web' => "http://example.com/"));
		// TEST INSERT
		$this->object->registerSave($author);
		$this->assertEquals(1, $this->object->count());
		$this->assertEquals(1, $this->object->count);
		$this->assertEquals(1, $this->object->getCount());
		$this->object->commit();
		$this->assertEquals(0, $this->object->count());
		$this->assertEquals(0, $this->object->count);
		$this->assertEquals(0, $this->object->getCount());
		$idRef = new PropertyReflection('App\Models\Author', 'id');
		$idRef->setAccessible(TRUE);
		$id = dibi::getInsertId();
		$this->assertEquals($id, $idRef->getValue($author));
		$this->assertEquals(
			array('id' => $id, 'name' => "Franta Skočdopole", 'web' => "http://example.com/"),
			(array)dibi::select("*")->from("authors")->where("[id] = %i", $id)->execute()->fetch()
		);
		// TEST UPDATE
		$nameRef = new PropertyReflection('App\Models\Author', 'name');
		$nameRef->setAccessible(TRUE);
		$nameRef->setValue($author, "Franta Vomáčka");
		$nameRef->setAccessible(FALSE);
		$this->object->registerSave($author);
		$this->assertEquals(1, $this->object->count());
		$this->assertEquals(1, $this->object->count);
		$this->assertEquals(1, $this->object->getCount());
		$this->object->commit();
		$this->assertEquals(0, $this->object->count());
		$this->assertEquals(0, $this->object->count);
		$this->assertEquals(0, $this->object->getCount());
		$this->assertEquals($id, $idRef->getValue($author));
		$this->assertEquals(
			array('id' => $id, 'name' => "Franta Vomáčka", 'web' => "http://example.com/"),
			(array)dibi::select("*")->from("authors")->where("[id] = %i", $id)->execute()->fetch()
		);
		$idRef->setAccessible(FALSE);
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

	public function testGetUnitOfWork()
	{
		$data = new UnitOfWork($this->manager);
		$this->assertEquals($data, UnitOfWork::getUnitOfWork($this->manager));
	}
}