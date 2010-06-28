<?php
namespace ActiveMapperTests;

require_once __DIR__ . "/../bootstrap.php";

use App\Models\Author;

class ServiceEntityTest extends \PHPUnit_Framework_TestCase
{
	public function testFind1()
	{
		$data = Author::find(1);
		$this->assertEquals(new Author(array('id' => 1, 'name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/")), $data);
		$this->assertType('App\Models\Author', $data);
   	}

	public function testFindBy1()
	{
		$data = Author::findByName('Patrik Votoček');
		$this->assertEquals(new Author(array('id' => 3, 'name' => "Patrik Votoček", 'web' => "http://patrik.votocek.cz/")), $data);
		$this->assertType('App\Models\Author', $data);
	}

	public function testFindBy2()
	{
		$data = Author::findById(2);
		$this->assertEquals(new Author(array('id' => 2, 'name' => "David Grudl", 'web' => "http://davidgrudl.com/")), $data);
		$this->assertType('App\Models\Author', $data);
	}

	public function testFindAll1()
	{
		$rows = Author::findAll();
		$this->assertType('ActiveMapper\RepositoryCollection', $rows);


		$data = array(
			new Author(array('id' => 1, 'name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/")),
			new Author(array('id' => 2, 'name' => "David Grudl", 'web' => "http://davidgrudl.com/")),
			new Author(array('id' => 3, 'name' => "Patrik Votoček", 'web' => "http://patrik.votocek.cz/")),
		);
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
}