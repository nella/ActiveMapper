<?php
namespace ActiveMapperTests\DataTypes;

require_once __DIR__ . "/../bootstrap.php";

class MyBase extends \ActiveMapper\DataTypes\Base {}

class BaseTest extends \PHPUnit_Framework_TestCase
{
	/** @var App\DataTypes\MyBase */
	private $object;

	public function setUp()
	{
		$this->object = new MyBase('test');
	}

	public function testGetName()
	{
		$this->assertEquals('test', $this->object->name);
		$this->assertEquals('test', $this->object->getName());
	}

	public function testSetNameException1()
	{
		$this->setExpectedException("MemberAccessException");
		$this->object->name = 'test';
	}

	public function testSetNameException2()
	{
		$this->setExpectedException("MemberAccessException");
		$this->object->setName('test');
	}

	public function testHasNullAllowed()
	{
		$this->assertFalse($this->object->allowNull);
		$this->assertFalse($this->object->isNullAllowed());
	}

	public function testSetNullException1()
	{
		$this->setExpectedException("MemberAccessException");
		$this->object->allowNull = TRUE;
	}

	public function testSetNullException2()
	{
		$this->setExpectedException("MemberAccessException");
		$this->object->setAllowNull(TRUE);
	}
}