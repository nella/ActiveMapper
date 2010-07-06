<?php
namespace ActiveMapperTests\DataTypes;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\DataTypes\Text;

class TextTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\DataTypes\Text */
	private $object;

	public function setUp()
	{
		$this->object = new Text('test');
	}

	public function testIsValidBoolean()
	{
		$this->assertFalse($this->object->isValid(TRUE));
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
		$object = new Text('test', TRUE);
		$this->assertTrue($object->isValid(NULL));
	}

	public function testConverToPHPValueString()
	{
		$data = $this->object->convertToPHPValue("Test");
		$this->assertType('string', $data);
		$this->assertEquals("Test", $data);
	}

	public function testToPHPNumber()
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
		$object = new Text('test', TRUE);
		$this->assertNull($object->convertToPHPValue(NULL));
	}
}