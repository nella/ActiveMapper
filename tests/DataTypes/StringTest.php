<?php
namespace App\DataTypes;

require_once __DIR__ . "/../bootstrap.php";
require_once "PHPUnit/Framework.php";

class StringTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\DataTypes\String */
	private $object;

	public function setUp()
	{
		$this->object = new \ActiveMapper\DataTypes\String('test', FALSE, 4);
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

	public function testValidateBoolean()
	{
		$this->assertFalse($this->object->validate(TRUE));
	}

	public function testValidateMaxLengthString()
	{
		$this->assertFalse($this->object->validate("Test test"));
	}

	public function testValidateString()
	{
		$this->assertTrue($this->object->validate("Test"));
	}

	public function testValidateNumber()
	{
		$this->assertTrue($this->object->validate(1));
	}

	public function testValidateDecimal()
	{
		$this->assertTrue($this->object->validate(1.1));
	}

	public function testValidateNull1()
	{
		$this->assertFalse($this->object->validate(NULL));
	}

	public function testValidateNull2()
	{
		$object = new \ActiveMapper\DataTypes\String('test', TRUE, 4);
		$this->assertTrue($object->validate(NULL));
	}

	public function testSanitizeString()
	{
		$data = $this->object->sanitize("Test");
		$this->assertType('string', $data);
		$this->assertEquals("Test", $data);
	}

	public function testSanitizeNumber()
	{
		$data = $this->object->sanitize(1);
		$this->assertType('string', $data);
		$this->assertEquals("1", $data);
	}

	public function testSanitizeDecimal()
	{
		$data = $this->object->sanitize(1.1);
		$this->assertType('string', $data);
		$this->assertEquals("1.1", $data);
	}

	public function testSanitizeMaxLenghtStringException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->sanitize("Test test");
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
		$object = new \ActiveMapper\DataTypes\String('test', TRUE, 4);
		$this->assertNull($object->sanitize(NULL));
	}
}