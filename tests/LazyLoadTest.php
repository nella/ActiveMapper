<?php
namespace App\Models;

require_once __DIR__ . "/bootstrap.php";
require_once "PHPUnit/Framework.php";

class LazyLoadTest extends \PHPUnit_Framework_TestCase
{
	public function testLoad1()
	{
		$object = new \ActiveMapper\LazyLoad('App\Models\Author', 'name', 1);

		$this->assertEquals("František Vomáčka", $object->getData());
		$this->assertEquals("František Vomáčka", $object->data);
	}

	public function testLoadBadEntityException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$object = new \ActiveMapper\LazyLoad('Exception', "test", 1);
	}

	public function testLoadBadColumnException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$object = new \ActiveMapper\LazyLoad('App\Models\Author', "exception", 1);
	}

	public function testLoadBadPrimaryKeyException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$object = new \ActiveMapper\LazyLoad('App\Models\Author', "test", 65555);
	}
}