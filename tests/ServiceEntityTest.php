<?php
namespace App;

require_once __DIR__ . "/bootstrap.php";
require_once "PHPUnit/Framework.php";

class ServiceEntityTest extends \PHPUnit_Framework_TestCase
{
	public function testFind1()
	{
		$data = Models\Author::find(1);
		$this->assertEquals(new Models\Author(array('id' => 1, 'name' => "František Vomáčka")), $data);
		$this->assertType('App\Models\Author', $data);
   	}

	public function testFindBy1()
	{
		$data = Models\Author::findByName('John Doe');
		$this->assertEquals(new Models\Author(array('id' => 2, 'name' => "John Doe")), $data);
		$this->assertType('App\Models\Author', $data);
	}

	public function testFindBy2()
	{
		$data = Models\Author::findById(1);
		$this->assertEquals(new Models\Author(array('id' => 1, 'name' => "František Vomáčka")), $data);
		$this->assertType('App\Models\Author', $data);
	}

	public function testFindAll1()
	{
		$rows = Models\Author::findAll();
		$this->assertType('ActiveMapper\RepositoryCollection', $rows);


		$data = array(
			new Models\Author(array('id' => 1, 'name' => "František Vomáčka")),
			new Models\Author(array('id' => 2, 'name' => "John Doe")),
			new Models\Author(array('id' => 3, 'name' => "Jan Novák")),
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