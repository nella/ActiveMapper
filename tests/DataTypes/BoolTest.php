<?php
namespace App\DataTypes;

require_once __DIR__ . "/../bootstrap.php";
require_once "PHPUnit/Framework.php";

class BoolTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\DataTypes\Bool */
	private $object;

	public function setUp()
	{
		$this->object = new \ActiveMapper\DataTypes\Bool('test');
	}

	public function testValidateString()
	{
		$this->assertFalse($this->object->validate("a"));
	}

	public function testValidateBigNumber()
	{
		$this->assertFalse($this->object->validate(2));
	}

	public function testValidateDecimal()
	{
		$this->assertFalse($this->object->validate(1.1));
	}

	public function testValidateTrue1()
	{
		$this->assertTrue($this->object->validate(TRUE));
	}

	public function testValidateTrue2()
	{
		$this->assertTrue($this->object->validate(1));
	}

	public function testValidateTrue3()
	{
		$this->assertTrue($this->object->validate("1"));
	}

	public function testValidateTrue4()
	{
		$this->assertTrue($this->object->validate("Y"));
	}

	public function testValidateTrue5()
	{
		$this->assertTrue($this->object->validate("TRUE"));
	}

	public function testValidateFalse1()
	{
		$this->assertTrue($this->object->validate(FALSE));
	}

	public function testValidateFalse2()
	{
		$this->assertTrue($this->object->validate(0));
	}

	public function testValidateFalse3()
	{
		$this->assertTrue($this->object->validate("0"));
	}

	public function testValidateFalse4()
	{
		$this->assertTrue($this->object->validate("N"));
	}

	public function testValidateFalse5()
	{
		$this->assertTrue($this->object->validate("FALSE"));
	}

	public function testValidateNull1()
	{
		$this->assertFalse($this->object->validate(NULL));
	}

	public function testValidateNull2()
	{
		$object = new \ActiveMapper\DataTypes\Bool('test', TRUE);
		$this->assertTrue($object->validate(NULL));
	}

	public function testSanitizeTrue1()
	{
		$data = $this->object->sanitize(TRUE);
		$this->assertType('bool', $data);
		$this->assertTrue($data);
	}

	public function testSanitizeTrue2()
	{
		$data = $this->object->sanitize(1);
		$this->assertType('bool', $data);
		$this->assertTrue($data);
	}

	public function testSanitizeTrue3()
	{
		$data = $this->object->sanitize("1");
		$this->assertType('bool', $data);
		$this->assertTrue($data);
	}

	public function testSanitizeTrue4()
	{
		$data = $this->object->sanitize("Y");
		$this->assertType('bool', $data);
		$this->assertTrue($data);
	}

	public function testSanitizeTrue5()
	{
		$data = $this->object->sanitize("TRUE");
		$this->assertType('bool', $data);
		$this->assertTrue($data);
	}

	public function testSanitizeFalse1()
	{
		$data = $this->object->sanitize(FALSE);
		$this->assertType('bool', $data);
		$this->assertFalse($data);
	}

	public function testSanitizeFalse2()
	{
		$data = $this->object->sanitize(0);
		$this->assertType('bool', $data);
		$this->assertFalse($data);
	}

	public function testSanitizeFalse3()
	{
		$data = $this->object->sanitize("0");
		$this->assertType('bool', $data);
		$this->assertFalse($data);
	}

	public function testSanitizeFalse4()
	{
		$data = $this->object->sanitize("N");
		$this->assertType('bool', $data);
		$this->assertFalse($data);
	}

	public function testSanitizeFalse5()
	{
		$data = $this->object->sanitize("FALSE");
		$this->assertType('bool', $data);
		$this->assertFalse($data);
	}

	public function testSanitizeStringException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->sanitize("a");
	}

	public function testSanitizeBigNumberException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->sanitize(2);
	}

	public function testSanitizeDecimalException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->sanitize(1.1);
	}

	public function testSanitizeNull1()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = $this->object->sanitize(NULL);
	}

	public function testSanitizeNull2()
	{
		$object = new \ActiveMapper\DataTypes\Bool('test', TRUE);
		$this->assertNull($object->sanitize(NULL));
	}
}