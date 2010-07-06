<?php
namespace ActiveMapperTests;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\DibiRepository,
	ActiveMapper\Manager,
	App\Models\Author;

class DibiRepositoryTest extends \PHPUnit_Framework_TestCase
{
	/** @var ActiveMapper\DibiRepository */
	private $object;
	/** @var ActiveMapper\Manager */
	private $manager;

	protected function setUp()
	{
		$this->manager = Manager::getManager();
		$this->object = new DibiRepository($this->manager, 'App\Models\Author');
	}

	public function testFind1()
	{
		$data = $this->object->find(1);
		$this->assertEquals(new Author(array('id' => 1, 'name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/")), $data);
		$this->assertType('App\Models\Author', $data);
		$this->assertSame($data, $this->object->find(1));
   	}

	public function testFind2()
	{
		$this->markTestSkipped();
		//$this->assertNull($this->object->find(13));
	}

	public function testFindBy1()
	{
		$data = $this->object->findByName('Patrik Votoček');
		$this->assertEquals(new Author(array('id' => 3, 'name' => "Patrik Votoček", 'web' => "http://patrik.votocek.cz/")), $data);
		$this->assertType('App\Models\Author', $data);
	}

	public function testFindBy2()
	{
		$data = $this->object->findById(2);
		$this->assertEquals(new Author(array('id' => 2, 'name' => "David Grudl", 'web' => "http://davidgrudl.com/")), $data);
		$this->assertType('App\Models\Author', $data);
	}

	public function testFindAll1()
	{
		$rows = $this->object->findAll();
		$this->markTestIncomplete();
		//$this->assertType('ActiveMapper\FluentCollection', $rows);

		$data = array(
			new Author(array('id' => 1, 'name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/")),
			new Author(array('id' => 2, 'name' => "David Grudl", 'web' => "http://davidgrudl.com/")),
			new Author(array('id' => 3, 'name' => "Patrik Votoček", 'web' => "http://patrik.votocek.cz/")),
		);
		$this->assertEquals(3, count($rows));
		$this->assertType('App\Models\Author', $rows[0]);
		$this->assertTrue(isset($rows[0]));
       	$this->assertEquals($data[0], $rows[0]);
		$this->assertEquals($data, $rows);
	}

	public function testCallException1()
	{
		$this->setExpectedException("MemberAccessException");
		DibiRepository::exception();
	}

	public function testCallException2()
	{
		$this->setExpectedException("MemberAccessException");
		$this->object->exception();
	}
	
	public function testGetRepository()
	{
		$data = new DibiRepository($this->manager, 'App\Models\Author');
		$this->assertEquals($data, DibiRepository::getRepository($this->manager, 'App\Models\Author'));
	}
}