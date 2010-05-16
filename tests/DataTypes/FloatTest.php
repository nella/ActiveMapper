<?php
namespace App\DataTypes;

require_once __DIR__ . "/../bootstrap.php";
require_once "PHPUnit/Framework.php";

class FloatTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\DataTypes\Float */
	private $object;

	public function setUp()
	{
		$this->object = new \ActiveMapper\DataTypes\Float('test');
	}

	public function testValidateString()
	{
		$this->assertFalse($this->object->validate("a"));
	}

	public function testValidateBoolean()
	{
		$this->assertFalse($this->object->validate(TRUE));
	}

	public function testValidateNumber()
	{
		$this->assertTrue($this->object->validate(1));
	}

	public function testValidateNumberString()
	{
		$this->assertTrue($this->object->validate("1"));
	}

	public function testValidateDecimal()
	{
		$this->assertTrue($this->object->validate(1.1));
	}

	public function testValidateDecimalString()
	{
		$this->assertTrue($this->object->validate("1.1"));
	}

	public function testValidateNull1()
	{
		$this->assertFalse($this->object->validate(NULL));
	}

	public function testValidateNull2()
	{
		$object = new \ActiveMapper\DataTypes\Float('test', TRUE);
		$this->assertTrue($object->validate(NULL));
	}

	public function testSanitizeNumber()
	{
		$data = $this->object->sanitize(1);
		$this->assertType('float', $data);
		$this->assertEquals(1, $data);
	}

	public function testSanitizeDecimal()
	{
		$data = $this->object->sanitize(1.1);
		$this->assertType('float', $data);
		$this->assertEquals(1.1, $data);
	}

	public function testSanitizeStringNumber()
	{
		$data = $this->object->sanitize("1");
		$this->assertType('float', $data);
		$this->assertEquals(1, $data);
	}

	public function testSanitizeStringDecimal()
	{
		$data = $this->object->sanitize("1.1");
		$this->assertType('float', $data);
		$this->assertEquals(1.1, $data);
	}

	public function testSanitizeStringException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->sanitize("a");
	}

	public function testSanitizeBooleanException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->sanitize(TRUE);
	}

	public function testSanitizeNull1()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->sanitize(NULL);
	}

	public function testSanitizeNull2()
	{
		$object = new \ActiveMapper\DataTypes\Float('test', TRUE);
		$this->assertNull($object->sanitize(NULL));
	}
}