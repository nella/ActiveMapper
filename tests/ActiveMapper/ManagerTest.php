<?php
namespace ActiveMapperTests;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\Manager,
	ActiveMapper\DibiRepository,
	ActiveMapper\DibiPersister,
	App\Models\Author,
	Nette\Reflection\PropertyReflection,
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
		$data = new \ActiveMapper\IdentityMap('App\Models\Author');
		$this->assertEquals($data, $this->object->getIdentityMap('App\Models\Author'));
	}

	public function testGetUnitOfWork()
	{
		$data = new \ActiveMapper\UnitOfWork($this->object);
		$this->assertEquals($data, $this->object->getUnitOfWork());
		$this->assertEquals($data, $this->object->unitOfWork);
	}

	public function testFind1()
	{
		$data = $this->object->find('App\Models\Author', 1);
		$this->assertEquals(author(array('id' => 1, 'name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/")), $data);
		$this->assertType('App\Models\Author', $data);
   	}

	public function testFindBy1()
	{
		$data = $this->object->findByName('App\Models\Author', 'Patrik Votoček');
		$this->assertEquals(author(array('id' => 3, 'name' => "Patrik Votoček", 'web' => "http://patrik.votocek.cz/")), $data);
		$this->assertType('App\Models\Author', $data);
	}

	public function testFindBy2()
	{
		$data = $this->object->findById('App\Models\Author', 2);
		$this->assertEquals(author(array('id' => 2, 'name' => "David Grudl", 'web' => "http://davidgrudl.com/")), $data);
		$this->assertType('App\Models\Author', $data);
	}

	public function testFindAll1()
	{
		$rows = $this->object->findAll('App\Models\Author');
		
		$this->markTestIncomplete();
		//$this->assertType('ActiveMapper\FluentCollection', $rows);

		$data = array(
			author(array('id' => 1, 'name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/")),
			author(array('id' => 2, 'name' => "David Grudl", 'web' => "http://davidgrudl.com/")),
			author(array('id' => 3, 'name' => "Patrik Votoček", 'web' => "http://patrik.votocek.cz/")),
		);
		$this->assertEquals(3, count($rows));
		$this->assertType('App\Models\Author', $rows[0]);
		$this->assertTrue(isset($rows[0]));
       	$this->assertEquals($data[0], $rows[0]);
		$this->assertEquals($data, $rows);
	}

	// HOW TO SPLIT TESTS
	public function testPersistingTotal()
	{
		$author = author(array('name' => "Franta Skočdopole", 'web' => "http://example.com/"));
		// TEST INSERT
		$this->object->persist($author);
		$this->object->flush();
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
		$this->object->persist($author);
		$this->object->flush();
		$this->assertEquals($id, $idRef->getValue($author));
		$this->assertEquals(
			array('id' => $id, 'name' => "Franta Vomáčka", 'web' => "http://example.com/"),
			(array)dibi::select("*")->from("authors")->where("[id] = %i", $id)->execute()->fetch()
		);
		$idRef->setAccessible(FALSE);
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
}