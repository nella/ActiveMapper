<?php
namespace ActiveMapperTests;

use App\Models\Author;

require_once __DIR__ . "/bootstrap.php";
require_once "PHPUnit/Framework.php";

class ServiceEntityTest extends \PHPUnit_Framework_TestCase
{
	public function testFind1()
	{
		$data = Author::find(1);
		$this->assertEquals(new Author(array('id' => 1, 'name' => "František Vomáčka")), $data);
		$this->assertType('App\Models\Author', $data);
   	}

	public function testFindBy1()
	{
		$data = Author::findByName('John Doe');
		$this->assertEquals(new Author(array('id' => 2, 'name' => "John Doe")), $data);
		$this->assertType('App\Models\Author', $data);
	}

	public function testFindBy2()
	{
		$data = Author::findById(1);
		$this->assertEquals(new Author(array('id' => 1, 'name' => "František Vomáčka")), $data);
		$this->assertType('App\Models\Author', $data);
	}

	public function testFindAll1()
	{
		$rows = Author::findAll();
		$this->assertType('ActiveMapper\RepositoryCollection', $rows);


		$data = array(
			new Author(array('id' => 1, 'name' => "František Vomáčka")),
			new Author(array('id' => 2, 'name' => "John Doe")),
			new Author(array('id' => 3, 'name' => "Jan Novák")),
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