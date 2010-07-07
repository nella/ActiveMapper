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
		$data = $object->getData();
		$this->assertEquals(5, $data[0]->id);
	}

	public function testManyToOneData()
	{
		$object = new LazyLoad($this->manager, 'App\Models\Application', 'author', array('author_id' => 3));
		$this->assertEquals(3, $object->getData()->id);
	}

	public function testManyToManyData1()
	{
		$object = new LazyLoad($this->manager, 'App\Models\Application', 'tags', array('id' => 2));
		$data = $object->getData();
		$this->assertEquals(4, $data[0]->id);
	}

	public function testManyToManyData2()
	{
		$object = new LazyLoad($this->manager, 'App\Models\Tag', 'applications', array('id' => 4));
		$data = $object->getData();
		$this->assertEquals(2, $data[0]->id);
	}
}