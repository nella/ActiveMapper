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

	public function testKeysOneToOne1()
	{
		$this->assertNull($this->object->find('App\Models\Author', 'blog', 1));
		$this->object->map('App\Models\Author', 'blog', 1, 1);
		$this->assertEquals(1, $this->object->find('App\Models\Author', 'blog', 1));
		$this->object->map('App\Models\Author', 'blog', 1, NULL);
		$this->assertNull($this->object->find('App\Models\Author', 'blog', 1));
	}

	public function testKeysOneToOne2()
	{
		$this->assertNull($this->object->find('App\Models\Blog', 'author', 1));
		$this->object->map('App\Models\Blog', 'author', 1, 1);
		$this->assertEquals(1, $this->object->find('App\Models\Blog', 'author', 1));
		$this->object->map('App\Models\Blog', 'author', 1, NULL);
		$this->assertNull($this->object->find('App\Models\Blog', 'author', 1));
	}

	public function testKeysOneToMany()
	{
		$this->assertNull($this->object->find('App\Models\Author', 'applications', 1));
		$this->object->map('App\Models\Author', 'applications', 1, array(1));
		$this->assertEquals(array(1), $this->object->find('App\Models\Author', 'applications', 1));
		$this->assertEquals(1, $this->object->find('App\Models\Application', 'author', 1));
		$this->object->map('App\Models\Author', 'applications', 1, NULL);
		$this->assertNull($this->object->find('App\Models\Author', 'applications', 1));
	}

	public function testKeysManyToOne()
	{
		$this->assertNull($this->object->find('App\Models\Application', 'author', 1));
		$this->object->map('App\Models\Application', 'author', 1, 1);
		$this->assertEquals(1, $this->object->find('App\Models\Application', 'author', 1));
		$this->object->map('App\Models\Application', 'author', 1, NULL);
		$this->assertNull($this->object->find('App\Models\Application', 'author', 1));
	}

	public function testKeysManyToMany1()
	{
		$this->assertNull($this->object->find('App\Models\Tag', 'applications', 1));
		$this->object->map('App\Models\Tag', 'applications', 1, array(1));
		$this->assertEquals(array(1), $this->object->find('App\Models\Tag', 'applications', 1));
		$this->object->map('App\Models\Tag', 'applications', 1, NULL);
		$this->assertNull($this->object->find('App\Models\Tag', 'applications', 1));
	}

	public function testKeysManyToMany2()
	{
		$this->assertNull($this->object->find('App\Models\Application', 'tags', 1));
		$this->object->map('App\Models\Application', 'tags', 1, array(1));
		$this->assertEquals(array(1), $this->object->find('App\Models\Application', 'tags', 1));
		$this->object->map('App\Models\Application', 'tags', 1, NULL);
		$this->assertNull($this->object->find('App\Models\Application', 'tags', 1));
	}
}