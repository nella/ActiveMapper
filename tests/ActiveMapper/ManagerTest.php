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
		$data = Manager::find('App\Models\Author', 1);
		$ent = new Author(array('id' => 1, 'name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/"));
		$this->assertEquals($ent, $data);
		$this->assertType('App\Models\Author', $data);
   	}

	public function testFindBy1()
	{
		$data = Manager::findByName('App\Models\Author', 'Patrik Votoček');
		$ent = new Author(array('id' => 3, 'name' => "Patrik Votoček", 'web' => "http://patrik.votocek.cz/"));
		$this->assertEquals($ent, $data);
		$this->assertType('App\Models\Author', $data);
	}

	public function testFindBy2()
	{
		$data = Manager::findById('App\Models\Author', 2);
		$ent = new Author(array('id' => 2, 'name' => "David Grudl", 'web' => "http://davidgrudl.com/"));
		$this->assertEquals($ent, $data);
		$this->assertType('App\Models\Author', $data);
	}

	public function testFindAll1()
	{
		$rows = Manager::findAll('App\Models\Author');
		$this->assertType('ActiveMapper\RepositoryCollection', $rows);


		$data = array(
			new Author(array('id' => 1, 'name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/")),
			new Author(array('id' => 2, 'name' => "David Grudl", 'web' => "http://davidgrudl.com/")),
			new Author(array('id' => 3, 'name' => "Patrik Votoček", 'web' => "http://patrik.votocek.cz/")),
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
		Manager::exception();
	}
}