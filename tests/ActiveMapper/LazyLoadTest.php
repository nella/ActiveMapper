<?php
namespace ActiveMapperTests;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\LazyLoad;

class LazyLoadTest extends \PHPUnit_Framework_TestCase
{
	public function testLoad1()
	{
		$object = new LazyLoad('App\Models\Author', 'name', 1);

		$this->assertEquals("Jakub Vrana", $object->getData());
		$this->assertEquals("Jakub Vrana", $object->data);
	}

	public function testLoadBadEntityException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$object = new LazyLoad('Exception', "test", 1);
	}

	public function testLoadBadColumnException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$object = new LazyLoad('App\Models\Author', "exception", 1);
	}

	public function testLoadBadPrimaryKeyException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$object = new LazyLoad('App\Models\Author', "test", 65555);
	}
}