<?php
namespace ActiveMapperTests\DataTypes;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\DataTypes\Date;

class DateTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\DataTypes\Date */
	private $object;

	public function setUp()
	{
		$this->object = new Date('test');
	}

	public function testIsValidString()
	{
		$this->assertFalse($this->object->isValid("a"));
	}

	public function testIsValidNumber()
	{
		$this->assertFalse($this->object->isValid(1));
	}

	public function testIsValidBoolean()
	{
		$this->assertFalse($this->object->isValid(TRUE));
	}

	public function testIsValidDate()
	{
		$this->assertTrue($this->object->isValid("2012-12-21"));
	}

	public function testIsValidNull1()
	{
		$this->assertFalse($this->object->isValid(NULL));
	}

	public function testIsValidNull2()
	{
		$object = new Date('test', TRUE);
		$this->assertTrue($object->isValid(NULL));
	}

	public function testConvertToPHPValueDate1()
	{
		$data = $this->object->convertToPHPValue("2012-12-21");
		$this->assertType('DateTime', $data);
		$this->assertEquals(new \DateTime("2012-12-21"), $data);
	}

	public function testConvertToPHPValueDate2()
	{
		$data = $this->object->convertToPHPValue(new \DateTime("2012-12-21"));
		$this->assertType('DateTime', $data);
		$this->assertEquals(new \DateTime("2012-12-21"), $data);
	}

	public function testConvertToPHPValueStringException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->convertToPHPValue("a");
	}

	public function testConvertToPHPValueNumberException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->convertToPHPValue(1);
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
		$object = new Date('test', TRUE);
		$this->assertNull($object->convertToPHPValue(NULL));
	}
}