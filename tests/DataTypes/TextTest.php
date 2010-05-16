<?php
namespace App\DataTypes;

require_once __DIR__ . "/../bootstrap.php";
require_once "PHPUnit/Framework.php";

class TextTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\DataTypes\Text */
	private $object;

	public function setUp()
	{
		$this->object = new \ActiveMapper\DataTypes\Text('test');
	}

	public function testValidateBoolean()
	{
		$this->assertFalse($this->object->validate(TRUE));
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
		$object = new \ActiveMapper\DataTypes\Text('test', TRUE);
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
		$object = new \ActiveMapper\DataTypes\Text('test', TRUE);
		$this->assertNull($object->sanitize(NULL));
	}
}