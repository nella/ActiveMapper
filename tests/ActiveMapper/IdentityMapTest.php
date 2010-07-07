<?php
namespace ActiveMapperTests;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\IdentityMap,
	App\Models\Author;

class IdentityMapTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\IdentityMap */
	private $object;

	public function setUp()
	{
		$this->object = new IdentityMap(\ActiveMapper\Manager::getManager(), 'App\Models\Author');
	}

	public function testFind()
	{
		$this->object->map(array('id' => 2, 'name' => "David Grudl", 'web' => "http://davidgrudl.com/"));
		$data = $this->object->find(2);
		$this->assertEquals(2, $data->id);
		$this->assertNull($this->object->find(9999999));
	}

	public function testStore_and_Detach_and_IsMapped()
	{
		$author = new Author(array('id' => 3, 'name' => "Patrik Votoček", 'web' => "http://patrik.votocek.cz/"));
		$this->assertFalse($this->object->isMapped($author));
		$this->object->store($author);
		$this->assertTrue($this->object->isMapped($author));
		$this->assertSame($author, $this->object->find(3));
		$this->object->detach($author);
		$this->assertFalse($this->object->isMapped($author));
		$this->assertNull($this->object->find(3));
	}

	public function testMap()
	{
		$data = $this->object->map(array('id' => 1, 'name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/"));
		$this->assertEquals(1, $data->id);
	}

	public function testMapException1()
	{
		$this->setExpectedException('InvalidArgumentException');
		$this->object->map('foo');
	}

	public function testMapException2()
	{
		$this->setExpectedException('InvalidArgumentException');
		$this->object->map(array());
	}

	public function testMapException3()
	{
		$this->setExpectedException('InvalidArgumentException');
		$this->object->map(array(array(1)));
	}
}