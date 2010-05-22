<?php
namespace App\Models;

require_once __DIR__ . "/bootstrap.php";
require_once "PHPUnit/Framework.php";

class ManagerTest extends \PHPUnit_Framework_TestCase
{
	public function testFind1()
	{
		$data = \ActiveMapper\Manager::find('App\Models\Author', 1);
		$ent = new Author(array('id' => 1, 'name' => "František Vomáčka"));
		$this->assertEquals($ent, $data);
		$this->assertType('App\Models\Author', $data);
   	}

	public function testFindBy1()
	{
		$data = \ActiveMapper\Manager::findByName('App\Models\Author', 'John Doe');
		$ent = new Author(array('id' => 2, 'name' => "John Doe"));
		$this->assertEquals($ent, $data);
		$this->assertType('App\Models\Author', $data);
	}

	public function testFindBy2()
	{
		$data = \ActiveMapper\Manager::findById('App\Models\Author', 1);
		$ent = new Author(array('id' => 1, 'name' => "František Vomáčka"));
		$this->assertEquals($ent, $data);
		$this->assertType('App\Models\Author', $data);
	}

	public function testFindAll1()
	{
		$rows = \ActiveMapper\Manager::findAll('App\Models\Author');
		$this->assertType('ActiveMapper\RepositoryCollection', $rows);


		$data = array(
			new \App\Models\Author(array('id' => 1, 'name' => "František Vomáčka")),
			new \App\Models\Author(array('id' => 2, 'name' => "John Doe")),
			new \App\Models\Author(array('id' => 3, 'name' => "Jan Novák")),
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
		\ActiveMapper\Manager::exception();
	}
}