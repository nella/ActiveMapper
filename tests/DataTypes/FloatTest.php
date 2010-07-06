<?php
namespace ActiveMapperTests\DataTypes;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\DataTypes\Float;

class FloatTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\DataTypes\Float */
	private $object;

	public function setUp()
	{
		$this->object = new Float('test');
	}

	public function testIsValidString()
	{
		$this->assertFalse($this->object->isValid("a"));
	}

	public function testIsValidBoolean()
	{
		$this->assertFalse($this->object->isValid(TRUE));
	}

	public function testIsValidNumber()
	{
		$this->assertTrue($this->object->isValid(1));
	}

	public function testIsValidNumberString()
	{
		$this->assertTrue($this->object->isValid("1"));
	}

	public function testIsValidDecimal()
	{
		$this->assertTrue($this->object->isValid(1.1));
	}

	public function testIsValidDecimalString()
	{
		$this->assertTrue($this->object->isValid("1.1"));
	}

	public function testIsValidNull1()
	{
		$this->assertFalse($this->object->isValid(NULL));
	}

	public function testIsValidNull2()
	{
		$object = new \ActiveMapper\DataTypes\Float('test', TRUE);
		$this->assertTrue($object->isValid(NULL));
	}

	public function testConvertToPHPValueNumber()
	{
		$data = $this->object->convertToPHPValue(1);
		$this->assertType('float', $data);
		$this->assertEquals(1, $data);
	}

	public function testConvertToPHPValueDecimal()
	{
		$data = $this->object->convertToPHPValue(1.1);
		$this->assertType('float', $data);
		$this->assertEquals(1.1, $data);
	}

	public function testConvertToPHPValueStringNumber()
	{
		$data = $this->object->convertToPHPValue("1");
		$this->assertType('float', $data);
		$this->assertEquals(1, $data);
	}

	public function testConvertToPHPValueStringDecimal()
	{
		$data = $this->object->convertToPHPValue("1.1");
		$this->assertType('float', $data);
		$this->assertEquals(1.1, $data);
	}

	public function testConvertToPHPValueStringException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->convertToPHPValue("a");
	}

	public function testConvertToPHPValueBooleanException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->convertToPHPValue(TRUE);
	}

	public function testConvertToPHPValueNull1()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->convertToPHPValue(NULL);
	}

	public function testConvertToPHPValueNull2()
	{
		$object = new Float('test', TRUE);
		$this->assertNull($object->convertToPHPValue(NULL));
	}
}