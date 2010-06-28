<?php
namespace ActiveMapperTests\MetaData;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\Entity,
	ActiveMapper\MetaData,
	ActiveMapper\Associations,
	ActiveMapper\Associations\OneToOne,
	ActiveMapper\Associations\ManyToMany;

class MyEntity extends Entity
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

class MyEntity2 extends Entity
{
	/**
	 * @column(exception)
	 */
	private $test;
}

class MyEntity3 extends Entity
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
class MyEntity4 extends Entity
{
	/**
	 * @column(Int)
	 * @autoincrement
	 * @primary
	 */
	private $testAutoincrementPrimary;
}

class MyEntity5 extends Entity
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

class MyEntity6 extends Entity {}

class MetadataTest extends \PHPUnit_Framework_TestCase
{
	public function testBoolColumns()
	{
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\Bool', $object->columns['testBool1']);
		$this->assertEquals('testBool1', $object->columns['testBool1']->getName());
		$this->assertFalse($object->columns['testBool1']->allowNull);
		$this->assertType('ActiveMapper\DataTypes\Bool', $object->columns['testBool2']);
		$this->assertEquals('testBool2', $object->columns['testBool2']->getName());
		$this->assertTrue($object->columns['testBool2']->allowNull);
	}

	public function testDateColumns()
	{
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\Date', $object->columns['testDate1']);
		$this->assertEquals('testDate1', $object->columns['testDate1']->getName());
		$this->assertFalse($object->columns['testDate1']->allowNull);
		$this->assertType('ActiveMapper\DataTypes\Date', $object->columns['testDate2']);
		$this->assertEquals('testDate2', $object->columns['testDate2']->getName());
		$this->assertTrue($object->columns['testDate2']->allowNull);
	}

	public function testDateTimeColumns()
	{
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\DateTime', $object->columns['testDateTime1']);
		$this->assertEquals('testDateTime1', $object->columns['testDateTime1']->getName());
		$this->assertFalse($object->columns['testDateTime1']->allowNull);
		$this->assertType('ActiveMapper\DataTypes\DateTime', $object->columns['testDateTime2']);
		$this->assertEquals('testDateTime2', $object->columns['testDateTime2']->getName());
		$this->assertTrue($object->columns['testDateTime2']->allowNull);
	}

	public function testFloatColumns()
	{
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\Float', $object->columns['testFloat1']);
		$this->assertEquals('testFloat1', $object->columns['testFloat1']->getName());
		$this->assertFalse($object->columns['testFloat1']->allowNull);
		$this->assertType('ActiveMapper\DataTypes\Float', $object->columns['testFloat2']);
		$this->assertEquals('testFloat2', $object->columns['testFloat2']->getName());
		$this->assertTrue($object->columns['testFloat2']->allowNull);
	}

	public function testIntColumns()
	{
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\Int', $object->columns['testInt1']);
		$this->assertEquals('testInt1', $object->columns['testInt1']->getName());
		$this->assertFalse($object->columns['testInt1']->allowNull);
		$this->assertType('ActiveMapper\DataTypes\Int', $object->columns['testInt2']);
		$this->assertEquals('testInt2', $object->columns['testInt2']->getName());
		$this->assertTrue($object->columns['testInt2']->allowNull);
	}

	public function testStringColumns()
	{
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\String', $object->columns['testString1']);
		$this->assertEquals('testString1', $object->columns['testString1']->getName());
		$this->assertFalse($object->columns['testString1']->allowNull);
		$this->assertEquals(128, $object->columns['testString1']->length);
		$this->assertType('ActiveMapper\DataTypes\String', $object->columns['testString2']);
		$this->assertEquals('testString2', $object->columns['testString2']->getName());
		$this->assertTrue($object->columns['testString2']->allowNull);
		$this->assertEquals(250, $object->columns['testString2']->length);
	}

	public function testTextColumns()
	{
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\Text', $object->columns['testText1']);
		$this->assertEquals('testText1', $object->columns['testText1']->getName());
		$this->assertFalse($object->columns['testText1']->allowNull);
		$this->assertType('ActiveMapper\DataTypes\Text', $object->columns['testText2']);
		$this->assertEquals('testText2', $object->columns['testText2']->getName());
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
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');
		$this->assertEquals('testInt1', $object->getPrimaryKey());
	}

	public function testWithoutPrimaryKeyException()
	{
		$this->setExpectedException('LogicException');
		new Metadata('ActiveMapperTests\MetaData\MyEntity6');
	}

	public function testPrimaryKeyMultipleException()
	{
		$this->setExpectedException('NotImplementedException');
		new Metadata('ActiveMapperTests\MetaData\MyEntity5');
	}

	public function testTableName()
	{
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');
		$this->assertEquals('my_entities', $object->getTableName());
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity4');
		$this->assertEquals('test', $object->getTableName());
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
		$this->assertEquals('testBool1', $column->getName());
		$this->assertFalse($column->allowNull);
	}

	public function testGetColumnException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$object = new Metadata('ActiveMapperTests\MetaData\MyEntity');
		$object->getColumn('exception');
	}

	/************************************************************ Associations ********************************************************p*v*/

	public function testOneToOneAssociation1()
	{
		$metadata = new Metadata('App\Models\Author');
		$object = new OneToOne('App\Models\Author', 'App\Models\Blog');
		$this->assertTrue(isset($metadata->associations['blog']));
		$this->assertEquals($object, $metadata->associations['blog']);

		$metadata = new Metadata('App\Models\Blog');
		$object = new OneToOne('App\Models\Blog', 'App\Models\Author', FALSE);
		$this->assertTrue(isset($metadata->associations['author']));
		$this->assertEquals($object, $metadata->associations['author']);
		$this->assertEquals(array('author_id'), $metadata->associationsKeys);
	}

	public function testOneToManyAssociation1()
	{
		$metadata = new Metadata('App\Models\Author');
		$object = new Associations\OneToMany('App\Models\Author', 'App\Models\Application');
		$this->assertTrue(isset($metadata->associations['applications']));
		$this->assertEquals($object, $metadata->associations['applications']);
	}

	public function testManyToOneAssociation1()
	{
		$metadata = new Metadata('App\Models\Application');
		$object = new Associations\ManyToOne('App\Models\Application', 'App\Models\Author');
		$this->assertTrue(isset($metadata->associations['author']));
		$this->assertEquals($object, $metadata->associations['author']);
		$this->assertEquals(array('author_id'), $metadata->associationsKeys);
	}

	public function testManyToManyAssociation1()
	{
		$metadata = new Metadata('App\Models\Application');
		$object = new ManyToMany('App\Models\Application', 'App\Models\Tag');
		$this->assertTrue(isset($metadata->associations['tags']));
		$this->assertEquals($object, $metadata->associations['tags']);

		$metadata = new Metadata('App\Models\Tag');
		$object = new ManyToMany('App\Models\Tag', 'App\Models\Application', FALSE);
		$this->assertTrue(isset($metadata->associations['applications']));
		$this->assertEquals($object, $metadata->associations['applications']);
	}

	public function testAssociationsKeys()
	{
		$metadata = new Metadata('App\Models\Application');
		$this->assertEquals(array('author_id'), $metadata->getAssociationsKeys());
		$this->assertEquals(array('author_id'), $metadata->associationsKeys);
		$metadata = new Metadata('App\Models\Blog');
		$this->assertEquals(array('author_id'), $metadata->getAssociationsKeys());
		$this->assertEquals(array('author_id'), $metadata->associationsKeys);
	}
}