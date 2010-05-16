<?php
namespace App\DataTypes;

require_once __DIR__ . "/../bootstrap.php";
require_once "PHPUnit/Framework.php";

class IntTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\DataTypes\Int */
	private $object;

	public function setUp()
	{
		$this->object = new \ActiveMapper\DataTypes\Int('test');
	}

	public function testValidateString()
	{
		$this->assertFalse($this->object->validate("a"));
	}

	public function testValidateBoolean()
	{
		$this->assertFalse($this->object->validate(TRUE));
	}

	public function testValidateDecimal()
	{
		$this->assertFalse($this->object->validate(1.1));
	}

	public function testValidateDecimalString()
	{
		$this->assertFalse($this->object->validate("1.1"));
	}

	public function testValidateNumber()
	{
		$this->assertTrue($this->object->validate(1));
	}

	public function testValidateNumberString()
	{
		$this->assertTrue($this->object->validate("1"));
	}

	public function testValidateNull1()
	{
		$this->assertFalse($this->object->validate(NULL));
	}

	public function testValidateNull2()
	{
		$object = new \ActiveMapper\DataTypes\Int('test', TRUE);
		$this->assertTrue($object->validate(NULL));
	}

	public function testSanitizeNumber()
	{
		$data = $this->object->sanitize(1);
		$this->assertType('int', $data);
		$this->assertEquals(1, $data);
	}

	public function testSanitizeStringNumber()
	{
		$data = $this->object->sanitize("1");
		$this->assertType('int', $data);
		$this->assertEquals(1, $data);
	}

	public function testSanitizeDecimalException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->sanitize(1.1);
	}

	public function testSanitizeStringDecimalException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->sanitize("1.1");
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
		$object = new \ActiveMapper\DataTypes\Int('test', TRUE);
		$this->assertNull($object->sanitize(NULL));
	}
}