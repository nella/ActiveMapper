<?php
namespace ActiveMapperTests;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\Proxy;

class FooEntity extends Proxy
{
	/**
	 * @column(Int)
	 * @primary
	 * @autoincrement
	 */
	protected $id;
	/**
	 * @column(Text)
	 */
	protected $text;
}

class ProxyTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapperTests\FooEntity */
	private $object;

	public function setUp()
	{
		$this->object = new FooEntity(array('id' => 1, 'text' => "Test text"));
	}

	public function testGetValue()
	{
		$this->assertEquals(1, $this->object->getId());
		$this->assertEquals(1, $this->object->id);
		$this->assertEquals("Test text", $this->object->getText());
		$this->assertEquals("Test text", $this->object->text);
	}

	public function testSetValue1()
	{
		$this->object->setText("XXX");
		$this->assertEquals("XXX", $this->object->text);
		$this->object->text = "Test text";
		$this->assertEquals("Test text", $this->object->text);
	}

	public function testSetPrimaryKeyException1()
	{
		$this->setExpectedException('MemberAccessException');
		$this->object->setId(0);
	}

	public function testSetPrimaryKeyException2()
	{
		$this->setExpectedException('MemberAccessException');
		$this->object->id = 0;
	}

	public function testInstanceException()
	{
		$this->setExpectedException('InvalidArgumentException');
		new FooEntity("test");
	}

	public function testGetValueException()
	{
		$this->setExpectedException('MemberAccessException');
		$this->object->exception;
	}

	public function testSetValueException()
	{
		$this->setExpectedException('MemberAccessException');
		$this->object->exception = 1;
	}

	public function testCallException()
	{
		$this->setExpectedException('MemberAccessException');
		$this->object->exception();
	}
}