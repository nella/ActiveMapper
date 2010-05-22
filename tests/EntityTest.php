<?php
namespace App;

require_once __DIR__ . "/bootstrap.php";
require_once "PHPUnit/Framework.php";

class FooEntity extends \ActiveMapper\Entity
{
	/**
	 * @column(Bool)
	 */
	private $testBool1;
	/**
	 * @column(Bool)
	 * @null
	 */
	private $testBool2;
	/**
	 * @column(Date)
	 */
	private $testDate1;
	/**
	 * @column(Date)
	 * @null
	 */
	private $testDate2;
	/**
	 * @column(DateTime)
	 */
	private $testDateTime1;
	/**
	 * @column(DateTime)
	 * @null
	 */
	private $testDateTime2;
	/**
	 * @column(Float)
	 */
	private $testFloat1;
	/**
	 * @column(Float)
	 * @null
	 */
	private $testFloat2;
	/**
	 * @column(Int)
	 */
	private $testInt1;
	/**
	 * @column(Int)
	 * @null
	 */
	private $testInt2;
	/**
	 * @column(String, 128)
	 */
	private $testString1;
	/**
	 * @column(String, 250)
	 * @null
	 */
	private $testString2;
	/**
	 * @column(Text)
	 */
	private $testText1;
	/**
	 * @column(Text)
	 * @null
	 */
	private $testText2;
	/**
	 * @column(Time)
	 */
	private $testTime1;
	/**
	 * @column(Time)
	 * @null
	 */
	private $testTime2;
}

class EntityTest extends \PHPUnit_Framework_TestCase
{
	/** @var App\FooEntity */
	private $object;

	public function setUp()
	{
		$this->object = new FooEntity(array(
		'testBool1' => TRUE,
		'testDate1' => "2012-12-21",
		'testDateTime1' => "2012-12-21 23:59:59",
		'testFloat1' => 3.14,
		'testInt1' => 13,
		'testString1' => "Test",
		'testText1' => "Test",
		'testTime1' => "23:59:59"
		));
	}

	public function testGetValue()
	{
		$this->assertTrue($this->object->testBool1);
		$this->assertType('bool', $this->object->testBool1);
		$this->assertNull($this->object->testBool2);
		$this->assertEquals(new \DateTime("2012-12-21"), $this->object->testDate1);
		$this->assertType('DateTime', $this->object->testDate1);
		$this->assertNull($this->object->testDate2);
		$this->assertEquals(new \DateTime("2012-12-21 23:59:59"), $this->object->testDateTime1);
		$this->assertType('DateTime', $this->object->testDateTime1);
		$this->assertNull($this->object->testDateTime2);
		$this->assertEquals(3.14, $this->object->testFloat1);
		$this->assertType('float', $this->object->testFloat1);
		$this->assertNull($this->object->testFloat2);
		$this->assertEquals(13, $this->object->testInt1);
		$this->assertType('int', $this->object->testInt1);
		$this->assertNull($this->object->testInt2);
		$this->assertEquals("Test", $this->object->testString1);
		$this->assertType('string', $this->object->testString1);
		$this->assertNull($this->object->testString2);
		$this->assertEquals("Test", $this->object->testText1);
		$this->assertType('string', $this->object->testText1);
		$this->assertNull($this->object->testText2);
		$this->assertEquals(new \DateTime("23:59:59"), $this->object->testTime1);
		$this->assertType('DateTime', $this->object->testTime1);
		$this->assertNull($this->object->testTime2);
	}

	public function testSetValue()
	{
		$this->object->testBool1 = FALSE;
		$this->assertFalse($this->object->testBool1);
		$this->object->testDate1 = "1978-01-23";
		$this->assertEquals(new \DateTime("1978-01-23"), $this->object->testDate1);
		$this->object->testDateTime1 = "1978-01-23 22:59:59";
		$this->assertEquals(new \DateTime("1978-01-23 22:59:59"), $this->object->testDateTime1);
		$this->object->testFloat1 = 4.66;
		$this->assertEquals(4.66, $this->object->testFloat1);
		$this->object->testInt1 = 9;
		$this->assertEquals(9, $this->object->testInt1);
		$this->object->testString1 = "Lorem Ipsum";
		$this->assertEquals("Lorem Ipsum", $this->object->testString1);
		$this->object->testText1 = "Lorem Ipsum";
		$this->assertEquals("Lorem Ipsum", $this->object->testText1);
		$this->object->testTime1 = "22:59:59";
		$this->assertEquals(new \DateTime("22:59:59"), $this->object->testTime1);
	}

	public function testSetNullValueExceptionBool()
	{
		$this->setExpectedException("InvalidArgumentException");
		$this->object->testBool1 = NULL;
	}

	public function testSetNullValueExceptionDate()
	{
		$this->setExpectedException("InvalidArgumentException");
		$this->object->testDate1 = NULL;
	}

	public function testSetNullValueExceptionDateTime()
	{
		$this->setExpectedException("InvalidArgumentException");
		$this->object->testDateTime1 = NULL;
	}

	public function testSetNullValueExceptionFloat()
	{
		$this->setExpectedException("InvalidArgumentException");
		$this->object->testFloat1 = NULL;
	}

	public function testSetNullValueExceptionInt()
	{
		$this->setExpectedException("InvalidArgumentException");
		$this->object->testInt1 = NULL;
	}

	public function testSetNullValueExceptionString()
	{
		$this->setExpectedException("InvalidArgumentException");
		$this->object->testString1 = NULL;
	}

	public function testSetNullValueExceptionText()
	{
		$this->setExpectedException("InvalidArgumentException");
		$this->object->testText1 = NULL;
	}

	public function testSetNullValueExceptionTime()
	{
		$this->setExpectedException("InvalidArgumentException");
		$this->object->testTime1 = NULL;
	}

	public function testGetNonExistPropertyException()
	{
		$this->setExpectedException("MemberAccessException");
		$data = $this->object->testNonExistProperty;
	}

	public function testSetNonExistPropertyException()
	{
		$this->setExpectedException("MemberAccessException");
		$this->object->testNonExistProperty = NULL;
	}

	public function testLazyLoad1()
	{
		$authors = Models\Author::findAll()->select()->fetchAll();
		$this->assertEquals("František Vomáčka", $authors[0]->name);
	}
}