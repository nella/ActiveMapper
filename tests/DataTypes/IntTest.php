<?php
namespace ActiveMapperTests\DataTypes;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\DataTypes\Int;

class IntTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\DataTypes\Int */
	private $object;

	public function setUp()
	{
		$this->object = new Int('test');
	}

	public function testIsValidString()
	{
		$this->assertFalse($this->object->isValid("a"));
	}

	public function testIsValidBoolean()
	{
		$this->assertFalse($this->object->isValid(TRUE));
	}

	public function testIsValidDecimal()
	{
		$this->assertFalse($this->object->isValid(1.1));
	}

	public function testIsValidDecimalString()
	{
		$this->assertFalse($this->object->isValid("1.1"));
	}

	public function testIsValidNumber()
	{
		$this->assertTrue($this->object->isValid(1));
	}

	public function testIsValidNumberString()
	{
		$this->assertTrue($this->object->isValid("1"));
	}

	public function testIsValidNull1()
	{
		$this->assertFalse($this->object->isValid(NULL));
	}

	public function testIsValidNull2()
	{
		$object = new Int('test', TRUE);
		$this->assertTrue($object->isValid(NULL));
	}

	public function testConvertToPHPValueNumber()
	{
		$data = $this->object->convertToPHPValue(1);
		$this->assertType('int', $data);
		$this->assertEquals(1, $data);
	}

	public function testConvertToPHPValueStringNumber()
	{
		$data = $this->object->convertToPHPValue("1");
		$this->assertType('int', $data);
		$this->assertEquals(1, $data);
	}

	public function testConvertToPHPValueDecimalException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->convertToPHPValue(1.1);
	}

	public function testConvertToPHPValueStringDecimalException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->convertToPHPValue("1.1");
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
		$object = new Int('test', TRUE);
		$this->assertNull($object->convertToPHPValue(NULL));
	}
}