<?php
namespace ActiveMapperTests;

use ActiveMapper\RepositoryCollection,
	App\Models\Author;

require_once __DIR__ . "/bootstrap.php";
require_once "PHPUnit/Framework.php";

class MyModel {}

class RepositoryCollectionTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\Collection */
	private $object;

	public function setUp()
	{
		$this->object = new RepositoryCollection('App\Models\Author');
	}

	public function testBadEntityException()
	{
		$this->setExpectedException("InvalidArgumentException");
		$data = new RepositoryCollection('App\Models\MyModel');
	}

	public function testWhere1()
	{
		$this->setUp();
		$fluent = $this->object->toFluent()->where("[id] = %i", 1);
		$this->object->where('id', 1);
		$this->assertEquals($fluent, $this->object->toFluent());
   	}

	public function testWhere2()
	{
		$this->setUp();
		$fluent = $this->object->toFluent()->where("[name] = %s", "test");
		$this->object->where('name', "test");
		$this->assertEquals($fluent, $this->object->toFluent());
   	}

	public function testWhere3()
	{
		$this->setUp();
		$fluent = $this->object->toFluent()->where("[id] = %i", 1)->where("[name] = %s", "test");
		$this->object->where('id', 1)->where('name', "test");
		$this->assertEquals($fluent, $this->object->toFluent());
   	}

	public function testWhereNot1()
	{
		$this->setUp();
		$fluent = $this->object->toFluent()->where("[id] != %i", 1);
		$this->object->whereNot('id', 1);
		$this->assertEquals($fluent, $this->object->toFluent());
   	}

	public function testWhereNot2()
	{
		$this->setUp();
		$fluent = $this->object->toFluent()->where("[name] != %s", "test");
		$this->object->whereNot('name', "test");
		$this->assertEquals($fluent, $this->object->toFluent());
   	}

	public function testWhereNot3()
	{
		$this->setUp();
		$fluent = $this->object->toFluent()->where("[id] != %i", 1)->where("[name] != %s", "test");
		$this->object->whereNot('id', 1)->whereNot('name', "test");
		$this->assertEquals($fluent, $this->object->toFluent());
   	}

	public function testWhereLike1()
	{
		$this->setUp();
		$fluent = $this->object->toFluent()->where("[name] LIKE %s", "%test%");
		$this->object->whereLike('name', "*test*");
		$this->assertEquals($fluent, $this->object->toFluent());
   	}

	public function testWhereLike2()
	{
		$this->setUp();
		$fluent = $this->object->toFluent()->where("[name] LIKE %s", "test%");
		$this->object->whereLike('name', "test*");
		$this->assertEquals($fluent, $this->object->toFluent());
   	}

	public function testWhereLike3()
	{
		$this->setUp();
		$fluent = $this->object->toFluent()->where("[name] LIKE %s", "%test");
		$this->object->whereLike('name', "*test");
		$this->assertEquals($fluent, $this->object->toFluent());
   	}

	public function testWhereNotLike1()
	{
		$this->setUp();
		$fluent = $this->object->toFluent()->where("[name] NOT LIKE %s", "%test%");
		$this->object->whereNotLike('name', "*test*");
		$this->assertEquals($fluent, $this->object->toFluent());
   	}

	public function testWhereNotLike2()
	{
		$this->setUp();
		$fluent = $this->object->toFluent()->where("[name] NOT LIKE %s", "test%");
		$this->object->whereNotLike('name', "test*");
		$this->assertEquals($fluent, $this->object->toFluent());
   	}

	public function testWhereNotLike3()
	{
		$this->setUp();
		$fluent = $this->object->toFluent()->where("[name] NOT LIKE %s", "%test");
		$this->object->whereNotLike('name', "*test");
		$this->assertEquals($fluent, $this->object->toFluent());
   	}

	public function testWhereIn1()
	{
		$this->setUp();
		$fluent = $this->object->toFluent()->where("[name] IN (%ex)", array("test1", "test2"));
		$this->object->whereIn('name', array("test1", "test2"));
		$this->assertEquals($fluent, $this->object->toFluent());
   	}

	public function testCount1()
	{
		$this->setUp();
       	$this->assertEquals(3, $this->object->count());
	}

	public function testFetchAll()
	{
		$this->setUp();
		$rows = $this->object->fetchAll();
		$data = array(
			new Author(array('id' => 1, 'name' => "František Vomáčka")),
			new Author(array('id' => 2, 'name' => "John Doe")),
			new Author(array('id' => 3, 'name' => "Jan Novák")),
		);
		$this->assertEquals(3, count($rows));
		$this->assertType('ActiveMapper\Collection', $rows);
		$this->assertType('App\Models\Author', $rows[0]);
		$this->assertTrue(isset($rows[0]));
       	$this->assertEquals($data[0], $rows[0]);
		$this->assertType('App\Models\Author', $rows[1]);
		$this->assertTrue(isset($rows[1]));
		$this->assertEquals($data[1], $rows[1]);
		$this->assertType('App\Models\Author', $rows[2]);
		$this->assertTrue(isset($rows[2]));
		$this->assertEquals($data[2], $rows[2]);

		unset($rows[2]);
		$this->assertFalse(isset($rows[2]));
		
		$data = new Author(array('id' => 4, 'name' => "Josef Pinďa"));
       	$rows[2] = $data;
		$this->assertEquals($data, $rows[2]);
   	}

	public function testFetchAllException()
	{
		$this->testCount1();
		$this->setExpectedException("InvalidStateException");
		$this->object->fetchAll();
   	}

	public function testConvertToDibiFluent()
	{
		$this->assertType('DibiFluent', $this->object->toFluent());
	}
}