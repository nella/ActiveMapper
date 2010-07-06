<?php
namespace ActiveMapperTests\DataTypes;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\DataTypes\Bool;

class BoolTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\DataTypes\Bool */
	private $object;

	public function setUp()
	{
		$this->object = new Bool('test');
	}

	public function testIsValidString()
	{
		$this->assertFalse($this->object->isValid("a"));
	}

	public function testIsValidBigNumber()
	{
		$this->assertFalse($this->object->isValid(2));
	}

	public function testIsValidDecimal()
	{
		$this->assertFalse($this->object->isValid(1.1));
	}

	public function testIsValidTrue1()
	{
		$this->assertTrue($this->object->isValid(TRUE));
	}

	public function testIsValidTrue2()
	{
		$this->assertTrue($this->object->isValid(1));
	}

	public function testIsValidTrue3()
	{
		$this->assertTrue($this->object->isValid("1"));
	}

	public function testIsValidTrue4()
	{
		$this->assertTrue($this->object->isValid("Y"));
	}

	public function testIsValidTrue5()
	{
		$this->assertTrue($this->object->isValid("TRUE"));
	}

	public function testIsValidFalse1()
	{
		$this->assertTrue($this->object->isValid(FALSE));
	}

	public function testIsValidFalse2()
	{
		$this->assertTrue($this->object->isValid(0));
	}

	public function testIsValidFalse3()
	{
		$this->assertTrue($this->object->isValid("0"));
	}

	public function testIsValidFalse4()
	{
		$this->assertTrue($this->object->isValid("N"));
	}

	public function testIsValidFalse5()
	{
		$this->assertTrue($this->object->isValid("FALSE"));
	}

	public function testIsValidNull1()
	{
		$this->assertFalse($this->object->isValid(NULL));
	}

	public function testIsValidNull2()
	{
		$object = new Bool('test', TRUE);
		$this->assertTrue($object->isValid(NULL));
	}

	public function testConvertToPHPValueTrue1()
	{
		$data = $this->object->convertToPHPValue(TRUE);
		$this->assertType('bool', $data);
		$this->assertTrue($data);
	}

	public function testConvertToPHPValueTrue2()
	{
		$data = $this->object->convertToPHPValue(1);
		$this->assertType('bool', $data);
		$this->assertTrue($data);
	}

	public function testConvertToPHPValueTrue3()
	{
		$data = $this->object->convertToPHPValue("1");
		$this->assertType('bool', $data);
		$this->assertTrue($data);
	}

	public function testConvertToPHPValueTrue4()
	{
		$data = $this->object->convertToPHPValue("Y");
		$this->assertType('bool', $data);
		$this->assertTrue($data);
	}

	public function testConvertToPHPValueTrue5()
	{
		$data = $this->object->convertToPHPValue("TRUE");
		$this->assertType('bool', $data);
		$this->assertTrue($data);
	}

	public function testConvertToPHPValueFalse1()
	{
		$data = $this->object->convertToPHPValue(FALSE);
		$this->assertType('bool', $data);
		$this->assertFalse($data);
	}

	public function testConvertToPHPValueFalse2()
	{
		$data = $this->object->convertToPHPValue(0);
		$this->assertType('bool', $data);
		$this->assertFalse($data);
	}

	public function testConvertToPHPValueFalse3()
	{
		$data = $this->object->convertToPHPValue("0");
		$this->assertType('bool', $data);
		$this->assertFalse($data);
	}

	public function testConvertToPHPValueFalse4()
	{
		$data = $this->object->convertToPHPValue("N");
		$this->assertType('bool', $data);
		$this->assertFalse($data);
	}

	public function testConvertToPHPValueFalse5()
	{
		$data = $this->object->convertToPHPValue("FALSE");
		$this->assertType('bool', $data);
		$this->assertFalse($data);
	}

	public function testConvertToPHPValueStringException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->convertToPHPValue("a");
	}

	public function testConvertToPHPValueBigNumberException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->convertToPHPValue(2);
	}

	public function testConvertToPHPValueDecimalException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->convertToPHPValue(1.1);
	}

	public function testConvertToPHPValueNull1()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->convertToPHPValue(NULL);
	}

	public function testConvertToPHPValueNull2()
	{
		$object = new Bool('test', TRUE);
		$this->assertNull($object->convertToPHPValue(NULL));
	}
}