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
	/** @var ActiveMapper\Manager */
	private $manager;

	public function setUp()
	{
		$this->object = new FooEntity(array('id' => 1, 'text' => "Test text"));
		$this->manager = \ActiveMapper\Manager::getManager();
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

	public function testOneToOneData1()
	{
		$author = $this->manager->find('App\Models\Author', 3);
		$this->assertEquals(3, $author->blog()->id);
	}

	public function testOneToOneData2()
	{
		$blog = $this->manager->find('App\Models\Blog', 3);
		$this->assertEquals(3, $blog->author()->id);
	}

	public function testOneToManyData()
	{
		$author = $this->manager->find('App\Models\Author', 3);
		$data = $author->applications();
		$this->assertEquals(5, $data[0]->id);
	}

	public function testManyToOneData()
	{
		$application = $this->manager->find('App\Models\Application', 6);
		$this->assertEquals(3, $application->author()->id);
	}

	public function testManyToManyData1()
	{
		$application = $this->manager->find('App\Models\Application', 2);
		$data = $application->tags();
		$this->assertEquals(4, $data[0]->id);
	}

	public function testManyToManyData2()
	{
		$tag = $this->manager->find('App\Models\Tag', 4);
		$data = $tag->applications();
		$this->assertEquals(2, $data[0]->id);
	}
}