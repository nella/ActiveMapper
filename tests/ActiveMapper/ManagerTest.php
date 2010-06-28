<?php
namespace ActiveMapperTests;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\Manager,
	App\Models\Author;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
	public function testGetEntityMetaData1()
	{
		$metadata = Manager::getEntityMetaData('App\Models\Author');
		$metadata->associations;
		$object = new \ActiveMapper\Metadata('App\Models\Author');
		$object->associations;
		$this->assertEquals($object, $metadata);
		$metadata = Manager::getEntityMetaData('App\Models\Author');
		$metadata->associations;
		$this->assertEquals($object, $metadata);
	}

	public function testFind1()
	{
		$this->markTestSkipped('loader');
		/*$data = Manager::find('App\Models\Author', 1);
		$ent = new Author(array('id' => 1, 'name' => "František Vomáčka"));
		$this->assertEquals($ent, $data);
		$this->assertType('App\Models\Author', $data);*/
   	}

	public function testFindBy1()
	{
		$this->markTestSkipped('loader');
		/*$data = Manager::findByName('App\Models\Author', 'John Doe');
		$ent = new Author(array('id' => 2, 'name' => "John Doe"));
		$this->assertEquals($ent, $data);
		$this->assertType('App\Models\Author', $data);*/
	}

	public function testFindBy2()
	{
		$this->markTestSkipped('loader');
		/*$data = Manager::findById('App\Models\Author', 1);
		$ent = new Author(array('id' => 1, 'name' => "František Vomáčka"));
		$this->assertEquals($ent, $data);
		$this->assertType('App\Models\Author', $data);*/
	}

	public function testFindAll1()
	{
		$this->markTestSkipped('loader');
		/*$rows = Manager::findAll('App\Models\Author');
		$this->assertType('ActiveMapper\RepositoryCollection', $rows);


		$data = array(
			new Author(array('id' => 1, 'name' => "František Vomáčka")),
			new Author(array('id' => 2, 'name' => "John Doe")),
			new Author(array('id' => 3, 'name' => "Jan Novák")),
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
		$this->assertEquals($data[2], $rows[2]);*/
	}

	public function testCallException()
	{
		$this->setExpectedException("MemberAccessException");
		Manager::exception();
	}
}