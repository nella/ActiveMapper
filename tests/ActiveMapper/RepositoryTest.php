<?php
namespace ActiveMapperTests;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\Repository,
	App\Models\Author;

class RepositoryTest extends \PHPUnit_Framework_TestCase
{
	public function testFind1()
	{
		$repository = new Repository('App\Models\Author');
		$data = $repository->find(1);
		$ent = new Author(array('id' => 1, 'name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/"));
		$this->assertEquals($ent, $data);
		$this->assertType('App\Models\Author', $data);
   	}

	public function testFindBy1()
	{
		$repository = new Repository('App\Models\Author');
		$data = $repository->findByName('Patrik Votoček');
		$ent = new Author(array('id' => 3, 'name' => "Patrik Votoček", 'web' => "http://patrik.votocek.cz/"));
		$this->assertEquals($ent, $data);
		$this->assertType('App\Models\Author', $data);
	}

	public function testFindBy2()
	{
		$repository = new Repository('App\Models\Author');
		$data = $repository->findById(2);
		$ent = new Author(array('id' => 2, 'name' => "David Grudl", 'web' => "http://davidgrudl.com/"));
		$this->assertEquals($ent, $data);
		$this->assertType('App\Models\Author', $data);
	}

	public function testFindAll1()
	{
		$repository = new Repository('App\Models\Author');
		$rows = $repository->findAll();
		$this->assertType('ActiveMapper\RepositoryCollection', $rows);


		$data = array(
			new \App\Models\Author(array('id' => 1, 'name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/")),
			new \App\Models\Author(array('id' => 2, 'name' => "David Grudl", 'web' => "http://davidgrudl.com/")),
			new \App\Models\Author(array('id' => 3, 'name' => "Patrik Votoček", 'web' => "http://patrik.votocek.cz/")),
		);
		$this->assertEquals(3, count($rows));
		$this->assertType('ActiveMapper\Collection', $rows);
		$this->assertType('App\Models\Author', $rows[0]);
		$this->assertTrue(isset($rows[0]));
       	$this->assertEquals($data[0], $rows[0]);
		$this->assertType('App\Models\Author', $rows[1]);
		$this->assertTrue(isset($rows[1]));
		$this->assertEquals($data[1], $rows[1]);
		$this->assertType('App\Models\Author', $rows[2]);
		$this->assertTrue(isset($rows[2]));
		$this->assertEquals($data[2], $rows[2]);
	}

	public function testCallException()
	{
		$this->setExpectedException("MemberAccessException");
		$repository = new Repository('App\Models\Author');
		$repository->exception();
	}
	
	public function testFactory()
	{
		$this->assertEquals(Repository::factory('App\Models\Author'), new Repository('App\Models\Author'));
	}
}