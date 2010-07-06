<?php
namespace ActiveMapperTests;

require_once __DIR__ . "/../bootstrap.php";

use ActiveMapper\Tools;

class ToolsTest extends \PHPUnit_Framework_TestCase {

	public function testPluralize()
	{
		$this->assertEquals('dogs', Tools::pluralize('dog'));
		$this->assertEquals('Dogs', Tools::pluralize('Dog'));
		$this->assertEquals('DOGs', Tools::pluralize('DOG'));

		$this->assertEquals('quizzes', Tools::pluralize('quiz'));
		$this->assertEquals('tomatoes', Tools::pluralize('tomato'));
		$this->assertEquals('mice', Tools::pluralize('mouse'));
		$this->assertEquals('people', Tools::pluralize('person'));
		$this->assertEquals('equipment', Tools::pluralize('equipment'));
		$this->assertEquals('companies', Tools::pluralize('company'));


		$this->assertEquals('dogs', Tools::pluralize('dogs'));
		$this->assertEquals('Dogs', Tools::pluralize('Dogs'));
		$this->assertEquals('DOGs', Tools::pluralize('DOGs'));

		$this->assertEquals('quizzes', Tools::pluralize('quizzes'));
		$this->assertEquals('tomatoes', Tools::pluralize('tomatoes'));
		$this->assertEquals('mice', Tools::pluralize('mice'));
		$this->assertEquals('people', Tools::pluralize('people'));
		$this->assertEquals('equipment', Tools::pluralize('equipment'));
		$this->assertEquals('companies', Tools::pluralize('companies'));
	}

	public function testSingularize()
	{
		$this->assertEquals('dog', Tools::singularize('dogs'));
		$this->assertEquals('Dog', Tools::singularize('Dogs'));
		$this->assertEquals('DOG', Tools::singularize('DOGS'));

		$this->assertEquals('quiz', Tools::singularize('quizzes'));
		$this->assertEquals('tomato', Tools::singularize('tomatoes'));
		$this->assertEquals('mouse', Tools::singularize('mice'));
		$this->assertEquals('person', Tools::singularize('people'));
		$this->assertEquals('equipment', Tools::singularize('equipment'));
		$this->assertEquals('company', Tools::singularize('companies'));

		$this->assertEquals('dog', Tools::singularize('dog'));
		$this->assertEquals('Dog', Tools::singularize('Dog'));
		$this->assertEquals('DOG', Tools::singularize('DOG'));

		$this->assertEquals('quiz', Tools::singularize('quiz'));
		$this->assertEquals('tomato', Tools::singularize('tomato'));
		$this->assertEquals('mouse', Tools::singularize('mouse'));
		$this->assertEquals('person', Tools::singularize('person'));
		$this->assertEquals('equipment', Tools::singularize('equipment'));
		$this->assertEquals('company', Tools::singularize('company'));
	}

	public function testIsPlural()
	{
		$this->assertFalse(Tools::isPlural('dog'));
		$this->assertFalse(Tools::isPlural('Dog'));
		$this->assertFalse(Tools::isPlural('DOG'));
		$this->assertFalse(Tools::isPlural('quiz'));
		$this->assertFalse(Tools::isPlural('tomato'));
		$this->assertFalse(Tools::isPlural('mouse'));
		$this->assertFalse(Tools::isPlural('person'));
		$this->assertFalse(Tools::isPlural('company'));

		$this->assertTrue(Tools::isPlural('dogs'));
		$this->assertTrue(Tools::isPlural('Dogs'));
		$this->assertTrue(Tools::isPlural('DOGS'));
		$this->assertTrue(Tools::isPlural('quizzes'));
		$this->assertTrue(Tools::isPlural('tomatoes'));
		$this->assertTrue(Tools::isPlural('mice'));
		$this->assertTrue(Tools::isPlural('people'));
		$this->assertTrue(Tools::isPlural('equipment'));
		$this->assertTrue(Tools::isPlural('companies'));
	}

	public function testIsSingular()
	{
		$this->assertFalse(Tools::isSingular('dogs'));
		$this->assertFalse(Tools::isSingular('Dogs'));
		$this->assertFalse(Tools::isSingular('DOGS'));
		$this->assertFalse(Tools::isSingular('quizzes'));
		$this->assertFalse(Tools::isSingular('tomatoes'));
		$this->assertFalse(Tools::isSingular('mice'));
		$this->assertFalse(Tools::isSingular('people'));
		$this->assertFalse(Tools::isSingular('companies'));

		$this->assertTrue(Tools::isSingular('dog'));
		$this->assertTrue(Tools::isSingular('Dog'));
		$this->assertTrue(Tools::isSingular('DOG'));
		$this->assertTrue(Tools::isSingular('quiz'));
		$this->assertTrue(Tools::isSingular('tomato'));
		$this->assertTrue(Tools::isSingular('mouse'));
		$this->assertTrue(Tools::isSingular('person'));
		$this->assertTrue(Tools::isSingular('equipment'));
		$this->assertTrue(Tools::isSingular('company'));
	}

	public function testIsCountable()
	{
		$this->assertTrue(Tools::isCountable('tomatoes'));
		$this->assertFalse(Tools::isCountable('equipment'));
	}

	public function testIsIrregular()
	{
		$this->assertFalse(Tools::isIrregular('dogs'));
		$this->assertTrue(Tools::isIrregular('person'));
	}

	public function testOrdinalize()
	{
		$this->assertEquals("0th", Tools::ordinalize(0));
		$this->assertEquals("1st", Tools::ordinalize(1));
		$this->assertEquals("2nd", Tools::ordinalize(2));
		$this->assertEquals("3rd", Tools::ordinalize(3));
		$this->assertEquals("4th", Tools::ordinalize(4));
		$this->assertEquals("5th", Tools::ordinalize(5));
		$this->assertEquals("10th", Tools::ordinalize(10));
		$this->assertEquals("11th", Tools::ordinalize(11));
		$this->assertEquals("12th", Tools::ordinalize(12));
		$this->assertEquals("13th", Tools::ordinalize(13));
		$this->assertEquals("14th", Tools::ordinalize(14));
		$this->assertEquals("20th", Tools::ordinalize(20));
		$this->assertEquals("21st", Tools::ordinalize(21));
		$this->assertEquals("22nd", Tools::ordinalize(22));
		$this->assertEquals("23rd", Tools::ordinalize(23));
		$this->assertEquals("24th", Tools::ordinalize(24));
		$this->assertEquals("25th", Tools::ordinalize(25));
		$this->assertEquals("100th", Tools::ordinalize(100));
		$this->assertEquals("101st", Tools::ordinalize(101));
		$this->assertEquals("102nd", Tools::ordinalize(102));
		$this->assertEquals("103rd", Tools::ordinalize(103));
		$this->assertEquals("104th", Tools::ordinalize(104));
		$this->assertEquals("105th", Tools::ordinalize(105));
		$this->assertEquals("1000th", Tools::ordinalize(1000));
		$this->assertEquals("1001st", Tools::ordinalize(1001));
		$this->assertEquals("1002nd", Tools::ordinalize(1002));
		$this->assertEquals("1003rd", Tools::ordinalize(1003));
		$this->assertEquals("1004th", Tools::ordinalize(1004));
		$this->assertEquals("1005th", Tools::ordinalize(1005));
	}

	public function testCamelize()
	{
		$this->assertEquals('ActiveMapper', Tools::camelize('active_mapper', TRUE));
		$this->assertEquals('NetteFramework', Tools::camelize('nette_framework', TRUE));
		$this->assertEquals('Dibi', Tools::camelize('dibi', TRUE));
		$this->assertEquals('activeMapper', Tools::camelize('active_mapper'));
		$this->assertEquals('netteFramework', Tools::camelize('nette_framework'));
		$this->assertEquals('dibi', Tools::camelize('dibi'));
	}

	public function testUnderscore1()
	{
		$this->assertEquals('active_mapper', Tools::underscore('ActiveMapper'));
		$this->assertEquals('nette_framework', Tools::underscore('NetteFramework'));
		$this->assertEquals('dibi', Tools::underscore('dibi'));
		$this->assertEquals('dibi', Tools::underscore('Dibi'));
	}

	public function testInstanceException()
	{
		$this->setExpectedException('LogicException');
		new Tools();
	}
}