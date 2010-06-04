<?php
namespace ActiveMapperTests;

require_once __DIR__ . "/bootstrap.php";
require_once "PHPUnit/Framework.php";

class CollectionTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\Collection */
	private $object;

	public function setUp()
	{
		$this->object = new \ActiveMapper\Collection();
	}

	public function testOffsetSetException()
	{
		$this->setExpectedException("InvalidArgumentException");
		$this->object[2] = "test";
	}
}