<?php
namespace App\DataTypes;

require_once __DIR__ . "/../bootstrap.php";
require_once "PHPUnit/Framework.php";

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\DataTypes\DateTime */
	private $object;

	public function setUp()
	{
		$this->object = new \ActiveMapper\DataTypes\DateTime('test');
	}

	public function testValidateString()
	{
		$this->assertFalse($this->object->validate("a"));
	}

	public function testValidateNumber()
	{
		$this->assertFalse($this->object->validate(1));
	}

	public function testValidateBoolean()
	{
		$this->assertFalse($this->object->validate(TRUE));
	}

	public function testValidateDate()
	{
		$this->assertTrue($this->object->validate("2012-12-21 08:32:12"));
	}

	public function testValidateNull1()
	{
		$this->assertFalse($this->object->validate(NULL));
	}

	public function testValidateNull2()
	{
		$object = new \ActiveMapper\DataTypes\DateTime('test', TRUE);
		$this->assertTrue($object->validate(NULL));
	}

	public function testSanitizeDate1()
	{
		$data = $this->object->sanitize("2012-12-21 08:32:12");
		$this->assertType('DateTime', $data);
		$this->assertEquals(new \DateTime("2012-12-21 08:32:12"), $data);
	}

	public function testSanitizeDate2()
	{
		$data = $this->object->sanitize(new \DateTime("2012-12-21 08:32:12"));
		$this->assertType('DateTime', $data);
		$this->assertEquals(new \DateTime("2012-12-21 08:32:12"), $data);
	}

	public function testSanitizeStringException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->sanitize("a");
	}

	public function testSanitizeNumberException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->sanitize(1);
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
		$object = new \ActiveMapper\DataTypes\DateTime('test', TRUE);
		$this->assertNull($object->sanitize(NULL));
	}
}