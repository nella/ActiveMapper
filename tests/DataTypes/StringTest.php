<?php
namespace ActiveMapperTests\DataTypes;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\DataTypes\String;

class StringTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\DataTypes\String */
	private $object;

	public function setUp()
	{
		$this->object = new String('test', FALSE, 4);
	}

	public function testGetLength()
	{
		$this->assertEquals(4, $this->object->length);
		$this->assertEquals(4, $this->object->getLength());
	}

	public function testSetLengthException1()
	{
		$this->setExpectedException("MemberAccessException");
		$this->object->length = 2;
	}

	public function testSetLengthException2()
	{
		$this->setExpectedException("MemberAccessException");
		$this->object->setLength(2);
	}

	public function testIsValidBoolean()
	{
		$this->assertFalse($this->object->isValid(TRUE));
	}

	public function testIsValidMaxLengthString()
	{
		$this->assertFalse($this->object->isValid("Test test"));
	}

	public function testIsValidString()
	{
		$this->assertTrue($this->object->isValid("Test"));
	}

	public function testIsValidNumber()
	{
		$this->assertTrue($this->object->isValid(1));
	}

	public function testIsValidDecimal()
	{
		$this->assertTrue($this->object->isValid(1.1));
	}

	public function testIsValidNull1()
	{
		$this->assertFalse($this->object->isValid(NULL));
	}

	public function testIsValidNull2()
	{
		$object = new String('test', TRUE, 4);
		$this->assertTrue($object->isValid(NULL));
	}

	public function testConvertToPHPValueString()
	{
		$data = $this->object->convertToPHPValue("Test");
		$this->assertType('string', $data);
		$this->assertEquals("Test", $data);
	}

	public function testConvertToPHPValueNumber()
	{
		$data = $this->object->convertToPHPValue(1);
		$this->assertType('string', $data);
		$this->assertEquals("1", $data);
	}

	public function testConvertToPHPValueDecimal()
	{
		$data = $this->object->convertToPHPValue(1.1);
		$this->assertType('string', $data);
		$this->assertEquals("1.1", $data);
	}

	public function testConvertToPHPValueMaxLenghtStringException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->convertToPHPValue("Test test");
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
		$object = new String('test', TRUE, 4);
		$this->assertNull($object->convertToPHPValue(NULL));
	}
}