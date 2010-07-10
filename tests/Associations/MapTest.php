<?php
namespace ActiveMapperTests\Associations;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\Associations\Map,
	App\Models\Author;

class MapTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\Associations\Map */
	private $object;

	public function setUp()
	{
		$this->object = new Map(\ActiveMapper\Manager::getManager());
	}

	// HOW TO SPLIT TESTS
	public function testTotal1()
	{
		$this->assertFalse($this->object->isMapped('App\Models\Author', 'blog', 1));
		$this->object->map('App\Models\Author', 'blog', 1, 1);
		$this->assertTrue($this->object->isMapped('App\Models\Author', 'blog', 1));
		$this->assertEquals(1, $this->object->find('App\Models\Author', 'blog', 1));
		$this->object->map('App\Models\Author', 'blog', 1, 2);
		$this->assertTrue($this->object->isMapped('App\Models\Author', 'blog', 1));
		$this->assertEquals(2, $this->object->find('App\Models\Author', 'blog', 1));
		$this->object->map('App\Models\Author', 'blog', 1, NULL);
		$this->assertFalse($this->object->isMapped('App\Models\Author', 'blog', 1));
	}
}