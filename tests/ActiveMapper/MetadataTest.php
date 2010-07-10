<?php
namespace ActiveMapperTests\Metadata;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\Metadata,
	ActiveMapper\Proxy,
	ActiveMapper\Associations,
	ActiveMapper\Associations\OneToOne,
	ActiveMapper\Associations\ManyToMany,
	App\Models\Author,
	Nette\Reflection\PropertyReflection;

class MyEntity
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
	 * @primary
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
}

class MyEntity2
{
	/**
	 * @column(exception)
	 */
	private $test;
}

class MyEntity3
{
	/**
	 * @column(Text)
	 * @autoincrement
	 */
	private $testAutoincrementTest;
}

/**
 * @tableName test
 */
class MyEntity4
{
	/**
	 * @column(Int)
	 * @autoincrement
	 * @primary
	 */
	private $testAutoincrementPrimary;
}

class MyEntity5
{
	/**
	 * @column(Int)
	 * @primary
	 */
	private $testMultiplePrimary1;
	/**
	 * @column(Int)
	 * @primary
	 */
	private $testMultiplePrimary2;
}

class MyEntity6 {}

class MyEntity7
{
	/**
	 * @primary
	 */
	private $test;
}

class MyEntity8
{
	/**
	 * @column(Int)
	 * @autoincrement
	 */
	private $test;
}

class FooEntity
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

class MetadataTest extends \PHPUnit_Framework_TestCase
{
	public function testGetMetadata()
	{
		$data = new Metadata('App\Models\Author');
		$data->getAssociations();
		$tmp = Metadata::getMetadata('App\Models\Author');
		$tmp->getAssociations();
		$this->assertEquals($data, $tmp);
		$this->assertSame($tmp, Metadata::getMetadata('App\Models\Author'));
	}

	public function testGetName()
	{
		$data = new Metadata('App\Models\Author');
		$this->assertEquals("Author", $data->getName());
		$this->assertEquals("Author", $data->name);
	}

	public function testHasProxy()
	{
		$object = new Metadata('App\Models\Author');
		$this->assertTrue($object->hasProxy());
		$this->assertTrue($object->getProxy());
		$this->assertTrue($object->proxy);
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');
		$this->assertFalse($object->hasProxy());
		$this->assertFalse($object->getProxy());
		$this->assertFalse($object->proxy);
	}

	public function testBoolColumns()
	{
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\Bool', $object->columns['testBool1']);
		$this->assertEquals('testBool1', $object->columns['testBool1']->name);
		$this->assertFalse($object->columns['testBool1']->allowNull);
		$this->assertType('ActiveMapper\DataTypes\Bool', $object->columns['testBool2']);
		$this->assertEquals('testBool2', $object->columns['testBool2']->name);
		$this->assertTrue($object->columns['testBool2']->allowNull);
	}

	public function testDateColumns()
	{
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\Date', $object->columns['testDate1']);
		$this->assertEquals('testDate1', $object->columns['testDate1']->name);
		$this->assertFalse($object->columns['testDate1']->allowNull);
		$this->assertType('ActiveMapper\DataTypes\Date', $object->columns['testDate2']);
		$this->assertEquals('testDate2', $object->columns['testDate2']->name);
		$this->assertTrue($object->columns['testDate2']->allowNull);
	}

	public function testDateTimeColumns()
	{
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\DateTime', $object->columns['testDateTime1']);
		$this->assertEquals('testDateTime1', $object->columns['testDateTime1']->name);
		$this->assertFalse($object->columns['testDateTime1']->allowNull);
		$this->assertType('ActiveMapper\DataTypes\DateTime', $object->columns['testDateTime2']);
		$this->assertEquals('testDateTime2', $object->columns['testDateTime2']->name);
		$this->assertTrue($object->columns['testDateTime2']->allowNull);
	}

	public function testFloatColumns()
	{
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\Float', $object->columns['testFloat1']);
		$this->assertEquals('testFloat1', $object->columns['testFloat1']->name);
		$this->assertFalse($object->columns['testFloat1']->allowNull);
		$this->assertType('ActiveMapper\DataTypes\Float', $object->columns['testFloat2']);
		$this->assertEquals('testFloat2', $object->columns['testFloat2']->name);
		$this->assertTrue($object->columns['testFloat2']->allowNull);
	}

	public function testIntColumns()
	{
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\Int', $object->columns['testInt1']);
		$this->assertEquals('testInt1', $object->columns['testInt1']->name);
		$this->assertFalse($object->columns['testInt1']->allowNull);
		$this->assertType('ActiveMapper\DataTypes\Int', $object->columns['testInt2']);
		$this->assertEquals('testInt2', $object->columns['testInt2']->name);
		$this->assertTrue($object->columns['testInt2']->allowNull);
	}

	public function testStringColumns()
	{
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\String', $object->columns['testString1']);
		$this->assertEquals('testString1', $object->columns['testString1']->name);
		$this->assertFalse($object->columns['testString1']->allowNull);
		$this->assertEquals(128, $object->columns['testString1']->length);
		$this->assertType('ActiveMapper\DataTypes\String', $object->columns['testString2']);
		$this->assertEquals('testString2', $object->columns['testString2']->name);
		$this->assertTrue($object->columns['testString2']->allowNull);
		$this->assertEquals(250, $object->columns['testString2']->length);
	}

	public function testTextColumns()
	{
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\Text', $object->columns['testText1']);
		$this->assertEquals('testText1', $object->columns['testText1']->name);
		$this->assertFalse($object->columns['testText1']->allowNull);
		$this->assertType('ActiveMapper\DataTypes\Text', $object->columns['testText2']);
		$this->assertEquals('testText2', $object->columns['testText2']->name);
		$this->assertTrue($object->columns['testText2']->allowNull);
	}

	public function testColumnInvalidDataTypeException1()
	{
		$this->setExpectedException("ActiveMapper\InvalidDataTypeException");
		new Metadata('ActiveMapperTests\MetaData\MyEntity2');
	}

	public function testAutoIncrementBadDataTypeException()
	{
		$this->setExpectedException('ActiveMapper\InvalidDataTypeException');
		new Metadata('ActiveMapperTests\MetaData\MyEntity3');
	}

	public function testPrimaryKey()
	{
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity4');
		$this->assertEquals('testAutoincrementPrimary', $object->getPrimaryKey());
		$this->assertEquals('testAutoincrementPrimary', $object->primaryKey);
		$this->assertTrue($object->isPrimaryKeyAutoincrement());
		$this->assertTrue($object->getPrimaryKeyAutoincrement());
		$this->assertTrue($object->primaryKeyAutoincrement);
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');
		$this->assertEquals('testInt1', $object->primaryKey);
		$this->assertFalse($object->isPrimaryKeyAutoincrement());
		$this->assertFalse($object->getPrimaryKeyAutoincrement());
		$this->assertFalse($object->primaryKeyAutoincrement);
	}

	public function testWithoutPrimaryKeyException()
	{
		$this->setExpectedException('LogicException');
		new Metadata('ActiveMapperTests\MetaData\MyEntity6');
	}

	public function testPrimaryKeyNonColumnException()
	{
		$this->setExpectedException('NotImplementedException');
		new Metadata('ActiveMapperTests\Metadata\MyEntity7');
	}

	public function testPrimaryKeyMultipleException()
	{
		$this->setExpectedException('NotImplementedException');
		new Metadata('ActiveMapperTests\MetaData\MyEntity5');
	}

	public function testAutoincrementNonPrimaryKeyException()
	{
		$this->setExpectedException('NotImplementedException');
		new Metadata('ActiveMapperTests\Metadata\MyEntity8');
	}

	public function testTableName()
	{
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');
		$this->assertEquals('my_entities', $object->getTableName());
		$this->assertEquals('my_entities', $object->tableName);
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity4');
		$this->assertEquals('test', $object->getTableName());
		$this->assertEquals('test', $object->tableName);
	}

	public function testHasColumn()
	{
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');
		$this->assertTrue($object->hasColumn('testBool1'));
		$this->assertFalse($object->hasColumn('false'));
	}

	public function testGetColumn()
	{
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');
		$column = $object->getColumn('testBool1');
		$this->assertType('ActiveMapper\DataTypes\Bool', $column);
		$this->assertEquals('testBool1', $column->name);
		$this->assertFalse($column->allowNull);
	}

	public function testGetColumnException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');
		$object->getColumn('exception');
	}

	public function testGetInstance1()
	{
		$data = array('id' => 1, 'name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/");
		$this->assertEquals(1, Metadata::getMetadata('App\Models\Author')
			->getInstance(\ActiveMapper\Manager::getManager(), $data)->id);
	}

	public function testGetInstance2()
	{
		$data = new FooEntity();
		$propRef = new PropertyReflection('ActiveMapperTests\MetaData\FooEntity', 'text');
		$propRef->setAccessible(TRUE);
		$propRef->setValue($data, "Test text");
		$propRef->setAccessible(FALSE);
		$this->assertEquals(
			$data,
			Metadata::getMetadata('ActiveMapperTests\MetaData\FooEntity')
				->getInstance(\ActiveMapper\Manager::getManager(), array('text' => "Test text"))
		);
	}

	public function testGetValues1()
	{
		$data = array('id' => 1, 'name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/");
		$author = new Author($data);
		$this->assertEquals($data, Metadata::getMetadata('App\Models\Author')->getValues($author));
	}

	public function testGetValues2()
	{
		$author = new Author(array('id' => 1, 'name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/"));
		$data = array('name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/");
		$this->assertEquals($data, Metadata::getMetadata('App\Models\Author')->getValues($author, FALSE));
	}

	public function testGetPrimaryKeyValue()
	{
		$author = new Author(array('id' => 1, 'name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/"));
		$this->assertEquals(1, Metadata::getMetadata('App\Models\Author')->getPrimaryKeyValue($author));
	}

	public function testSetPrimaryKeyValue()
	{
		$author = new Author(array('name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/"));
		Metadata::getMetadata('App\Models\Author')->setPrimaryKeyValue($author, 1);
		$this->assertEquals(new Author(array('id' => 1, 'name' => "Jakub Vrana", 'web' => "http://www.vrana.cz/")), $author);
	}

	public function testOneToOneAssociation1()
	{
		$metadata = new Metadata('App\Models\Author');
		$object = new OneToOne('App\Models\Author', 'App\Models\Blog', 'author');
		$this->assertTrue(isset($metadata->associations['blog']));
		$this->assertTrue(isset($metadata->oneToOne['blog']));
		$this->assertEquals($object, $metadata->associations['blog']);
		$this->assertEquals($object, $metadata->oneToOne['blog']);

		$metadata = new Metadata('App\Models\Blog');
		$object = new OneToOne('App\Models\Blog', 'App\Models\Author');
		$this->assertTrue(isset($metadata->associations['author']));
		$this->assertTrue(isset($metadata->oneToOne['author']));
		$this->assertEquals($object, $metadata->associations['author']);
		$this->assertEquals($object, $metadata->oneToOne['author']);
	}

	public function testOneToManyAssociation1()
	{
		$metadata = new Metadata('App\Models\Author');
		$object = new Associations\OneToMany('App\Models\Author', 'App\Models\Application');
		$this->assertTrue(isset($metadata->associations['applications']));
		$this->assertTrue(isset($metadata->oneToMany['applications']));
		$this->assertEquals($object, $metadata->associations['applications']);
		$this->assertEquals($object, $metadata->oneToMany['applications']);
	}

	public function testManyToOneAssociation1()
	{
		$metadata = new Metadata('App\Models\Application');
		$object = new Associations\ManyToOne('App\Models\Application', 'App\Models\Author');
		$this->assertTrue(isset($metadata->associations['author']));
		$this->assertTrue(isset($metadata->manyToOne['author']));
		$this->assertEquals($object, $metadata->associations['author']);
		$this->assertEquals($object, $metadata->manyToOne['author']);
	}

	public function testManyToManyAssociation1()
	{
		$metadata = new Metadata('App\Models\Application');
		$object = new ManyToMany('App\Models\Application', 'App\Models\Tag');
		$this->assertTrue(isset($metadata->associations['tags']));
		$this->assertTrue(isset($metadata->manyToMany['tags']));
		$this->assertEquals($object, $metadata->associations['tags']);
		$this->assertEquals($object, $metadata->manyToMany['tags']);

		$metadata = new Metadata('App\Models\Tag');
		$object = new ManyToMany('App\Models\Tag', 'App\Models\Application', FALSE);
		$this->assertTrue(isset($metadata->associations['applications']));
		$this->assertTrue(isset($metadata->manyToMany['applications']));
		$this->assertEquals($object, $metadata->associations['applications']);
		$this->assertEquals($object, $metadata->manyToMany['applications']);
	}
}