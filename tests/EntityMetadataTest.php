<?php
namespace ActiveMapperTests\MetaData;

use ActiveMapper\Entity,
	ActiveMapper\EntityMetaData,
	ActiveMapper\Associations,
	ActiveMapper\Associations\OneToOne,
	ActiveMapper\Associations\ManyToMany;

require_once __DIR__ . "/bootstrap.php";
require_once "PHPUnit/Framework.php";

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
	/**
	 * @column(Time)
	 */
	private $testTime1;
	/**
	 * @column(Time)
	 * @null
	 */
	private $testTime2;
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

class EntityMetadataTest extends \PHPUnit_Framework_TestCase
{
	public function testBoolColumns()
	{
		$object = new EntityMetadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\Bool', $object->columns['testBool1']);
		$this->assertEquals('testBool1', $object->columns['testBool1']->getName());
		$this->assertFalse($object->columns['testBool1']->allowNull);
		$this->assertType('ActiveMapper\DataTypes\Bool', $object->columns['testBool2']);
		$this->assertEquals('testBool2', $object->columns['testBool2']->getName());
		$this->assertTrue($object->columns['testBool2']->allowNull);
	}

	public function testDateColumns()
	{
		$object = new EntityMetadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\Date', $object->columns['testDate1']);
		$this->assertEquals('testDate1', $object->columns['testDate1']->getName());
		$this->assertFalse($object->columns['testDate1']->allowNull);
		$this->assertType('ActiveMapper\DataTypes\Date', $object->columns['testDate2']);
		$this->assertEquals('testDate2', $object->columns['testDate2']->getName());
		$this->assertTrue($object->columns['testDate2']->allowNull);
	}

	public function testDateTimeColumns()
	{
		$object = new EntityMetadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\DateTime', $object->columns['testDateTime1']);
		$this->assertEquals('testDateTime1', $object->columns['testDateTime1']->getName());
		$this->assertFalse($object->columns['testDateTime1']->allowNull);
		$this->assertType('ActiveMapper\DataTypes\DateTime', $object->columns['testDateTime2']);
		$this->assertEquals('testDateTime2', $object->columns['testDateTime2']->getName());
		$this->assertTrue($object->columns['testDateTime2']->allowNull);
	}

	public function testFloatColumns()
	{
		$object = new EntityMetadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\Float', $object->columns['testFloat1']);
		$this->assertEquals('testFloat1', $object->columns['testFloat1']->getName());
		$this->assertFalse($object->columns['testFloat1']->allowNull);
		$this->assertType('ActiveMapper\DataTypes\Float', $object->columns['testFloat2']);
		$this->assertEquals('testFloat2', $object->columns['testFloat2']->getName());
		$this->assertTrue($object->columns['testFloat2']->allowNull);
	}

	public function testIntColumns()
	{
		$object = new EntityMetadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\Int', $object->columns['testInt1']);
		$this->assertEquals('testInt1', $object->columns['testInt1']->getName());
		$this->assertFalse($object->columns['testInt1']->allowNull);
		$this->assertType('ActiveMapper\DataTypes\Int', $object->columns['testInt2']);
		$this->assertEquals('testInt2', $object->columns['testInt2']->getName());
		$this->assertTrue($object->columns['testInt2']->allowNull);
	}

	public function testStringColumns()
	{
		$object = new EntityMetadata('ActiveMapperTests\MetaData\MyEntity');

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
		$object = new EntityMetadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\Text', $object->columns['testText1']);
		$this->assertEquals('testText1', $object->columns['testText1']->getName());
		$this->assertFalse($object->columns['testText1']->allowNull);
		$this->assertType('ActiveMapper\DataTypes\Text', $object->columns['testText2']);
		$this->assertEquals('testText2', $object->columns['testText2']->getName());
		$this->assertTrue($object->columns['testText2']->allowNull);
	}

	public function testTimeColumns()
	{
		$object = new EntityMetadata('ActiveMapperTests\MetaData\MyEntity');

		$this->assertType('ActiveMapper\DataTypes\Time', $object->columns['testTime1']);
		$this->assertEquals('testTime1', $object->columns['testTime1']->getName());
		$this->assertFalse($object->columns['testTime1']->allowNull);
		$this->assertType('ActiveMapper\DataTypes\Time', $object->columns['testTime2']);
		$this->assertEquals('testTime2', $object->columns['testTime2']->getName());
		$this->assertTrue($object->columns['testTime2']->allowNull);
	}

	public function testColumnInvalidDataTypeException1()
	{
		$this->setExpectedException("ActiveMapper\InvalidDataTypeException");
		new EntityMetadata('ActiveMapperTests\MetaData\MyEntity2');
	}

	public function testAutoIncrementBadDataTypeException()
	{
		$this->setExpectedException('ActiveMapper\InvalidDataTypeException');
		new EntityMetadata('ActiveMapperTests\MetaData\MyEntity3');
	}

	public function testHasPrimaryKey()
	{
		$object = new EntityMetadata('ActiveMapperTests\MetaData\MyEntity4');
		$this->assertTrue($object->hasPrimaryKey());
		$object = new EntityMetadata('ActiveMapperTests\MetaData\MyEntity');
		$this->assertFalse($object->hasPrimaryKey());
	}

	public function testPrimaryKey()
	{
		$object = new EntityMetadata('ActiveMapperTests\MetaData\MyEntity4');
		$this->assertEquals('testAutoincrementPrimary', $object->getPrimaryKey());
		$object = new EntityMetadata('ActiveMapperTests\MetaData\MyEntity');
		$this->assertNull($object->getPrimaryKey());
	}

	public function testPrimaryKeyMultipleException()
	{
		$this->setExpectedException('NotImplementedException');
		new EntityMetadata('ActiveMapperTests\MetaData\MyEntity5');
	}

	public function testTableName()
	{
		$object = new EntityMetadata('ActiveMapperTests\MetaData\MyEntity');
		$this->assertEquals('my_entities', $object->getTableName());
		$object = new EntityMetadata('ActiveMapperTests\MetaData\MyEntity4');
		$this->assertEquals('test', $object->getTableName());
	}

	public function testHasColumn()
	{
		$object = new EntityMetadata('ActiveMapperTests\MetaData\MyEntity');
		$this->assertTrue($object->hasColumn('testBool1'));
		$this->assertFalse($object->hasColumn('false'));
	}

	public function testGetColumn()
	{
		$object = new EntityMetadata('ActiveMapperTests\MetaData\MyEntity');
		$column = $object->getColumn('testBool1');
		$this->assertType('ActiveMapper\DataTypes\Bool', $column);
		$this->assertEquals('testBool1', $column->getName());
		$this->assertFalse($column->allowNull);
	}

	public function testGetColumnException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$object = new EntityMetadata('ActiveMapperTests\MetaData\MyEntity');
		$object->getColumn('exception');
	}

	/************************************************************ Associations ********************************************************p*v*/

	public function testOneToOneAssociation1()
	{
		$metadata = new EntityMetadata('App\Models\Author');
		$object = new OneToOne('App\Models\Author', 'App\Models\Profile');
		$this->assertTrue(isset($metadata->associations['profile']));
		$this->assertEquals($object, $metadata->associations['profile']);

		$metadata = new EntityMetadata('App\Models\Profile');
		$object = new OneToOne('App\Models\Profile', 'App\Models\Author', FALSE);
		$this->assertTrue(isset($metadata->associations['author']));
		$this->assertEquals($object, $metadata->associations['author']);
		$this->assertEquals(array('author_id'), $metadata->associationsKeys);
	}

	public function testOneToManyAssociation1()
	{
		$metadata = new EntityMetadata('App\Models\Author');
		$object = new Associations\OneToMany('App\Models\Author', 'App\Models\Article');
		$this->assertTrue(isset($metadata->associations['articles']));
		$this->assertEquals($object, $metadata->associations['articles']);
	}

	public function testManyToOneAssociation1()
	{
		$metadata = new EntityMetadata('App\Models\Article');
		$object = new Associations\ManyToOne('App\Models\Article', 'App\Models\Author');
		$this->assertTrue(isset($metadata->associations['author']));
		$this->assertEquals($object, $metadata->associations['author']);
		$this->assertEquals(array('author_id'), $metadata->associationsKeys);
	}

	public function testManyToManyAssociation1()
	{
		$metadata = new EntityMetadata('App\Models\Article');
		$object = new ManyToMany('App\Models\Article', 'App\Models\Tag');
		$this->assertTrue(isset($metadata->associations['tags']));
		$this->assertEquals($object, $metadata->associations['tags']);

		$metadata = new EntityMetadata('App\Models\Tag');
		$object = new ManyToMany('App\Models\Tag', 'App\Models\Article', FALSE);
		$this->assertTrue(isset($metadata->associations['articles']));
		$this->assertEquals($object, $metadata->associations['articles']);
	}

	public function testAssociationsKeys()
	{
		$metadata = new EntityMetadata('App\Models\Article');
		$this->assertEquals(array('author_id'), $metadata->getAssociationsKeys());
		$this->assertEquals(array('author_id'), $metadata->associationsKeys);
		$metadata = new EntityMetadata('App\Models\Profile');
		$this->assertEquals(array('author_id'), $metadata->getAssociationsKeys());
		$this->assertEquals(array('author_id'), $metadata->associationsKeys);
	}
}