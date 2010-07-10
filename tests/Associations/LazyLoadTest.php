<?php
namespace ActiveMapperTests\Associations;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\Associations\LazyLoad;

class LazyLoadTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\Manager */
	private $manager;

	protected function setUp()
	{
		$this->manager = \ActiveMapper\Manager::getManager();
	}

	public function testOneToOneData1()
	{
		$object = new LazyLoad($this->manager, 'App\Models\Author', 'blog', array('id' => 3));
		$this->assertEquals(3, $object->getData()->id);
	}

	public function testOneToOneData2()
	{
		$object = new LazyLoad($this->manager, 'App\Models\Blog', 'author', array('author_id' => 3));
		$this->assertEquals(3, $object->getData()->id);
	}

	public function testOneToManyData()
	{
		$object = new LazyLoad($this->manager, 'App\Models\Author', 'applications', array('id' => 3));
		$this->assertTrue(in_array(5, array_keys($object->getData())));
	}

	public function testManyToOneData()
	{
		$object = new LazyLoad($this->manager, 'App\Models\Application', 'author', array('author_id' => 3));
		$this->assertEquals(3, $object->getData()->id);
	}

	public function testManyToManyData1()
	{
		$object = new LazyLoad($this->manager, 'App\Models\Application', 'tags', array('id' => 2));
		$this->assertTrue(in_array(4, array_keys($object->getData())));
	}

	public function testManyToManyData2()
	{
		$object = new LazyLoad($this->manager, 'App\Models\Tag', 'applications', array('id' => 4));
		$this->assertTrue(in_array(2, array_keys($object->getData())));
	}
}