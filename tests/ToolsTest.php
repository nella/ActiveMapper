<?php
namespace App;

use \ActiveMapper\Tools;

require_once __DIR__ . "/bootstrap.php";
require_once 'PHPUnit/Framework.php';

class ToolsTest extends \PHPUnit_Framework_TestCase {

	public function testPluralize()
	{
		$this->assertSame('dogs', Tools::pluralize('dog'));
		$this->assertSame('Dogs', Tools::pluralize('Dog'));
		$this->assertSame('DOGs', Tools::pluralize('DOG'));

		$this->assertSame('quizzes', Tools::pluralize('quiz'));
		$this->assertSame('tomatoes', Tools::pluralize('tomato'));
		$this->assertSame('mice', Tools::pluralize('mouse'));
		$this->assertSame('people', Tools::pluralize('person'));
		$this->assertSame('equipment', Tools::pluralize('equipment'));
		$this->assertSame('companies', Tools::pluralize('company'));


		$this->assertSame('dogs', Tools::pluralize('dogs'));
		$this->assertSame('Dogs', Tools::pluralize('Dogs'));
		$this->assertSame('DOGs', Tools::pluralize('DOGs'));

		$this->assertSame('quizzes', Tools::pluralize('quizzes'));
		$this->assertSame('tomatoes', Tools::pluralize('tomatoes'));
		$this->assertSame('mice', Tools::pluralize('mice'));
		$this->assertSame('people', Tools::pluralize('people'));
		$this->assertSame('equipment', Tools::pluralize('equipment'));
		$this->assertSame('companies', Tools::pluralize('companies'));
	}

	public function testSingularize()
	{
		$this->assertSame('dog', Tools::singularize('dogs'));
		$this->assertSame('Dog', Tools::singularize('Dogs'));
		$this->assertSame('DOG', Tools::singularize('DOGS'));

		$this->assertSame('quiz', Tools::singularize('quizzes'));
		$this->assertSame('tomato', Tools::singularize('tomatoes'));
		$this->assertSame('mouse', Tools::singularize('mice'));
		$this->assertSame('person', Tools::singularize('people'));
		$this->assertSame('equipment', Tools::singularize('equipment'));
		$this->assertSame('company', Tools::singularize('companies'));

		$this->assertSame('dog', Tools::singularize('dog'));
		$this->assertSame('Dog', Tools::singularize('Dog'));
		$this->assertSame('DOG', Tools::singularize('DOG'));

		$this->assertSame('quiz', Tools::singularize('quiz'));
		$this->assertSame('tomato', Tools::singularize('tomato'));
		$this->assertSame('mouse', Tools::singularize('mouse'));
		$this->assertSame('person', Tools::singularize('person'));
		$this->assertSame('equipment', Tools::singularize('equipment'));
		$this->assertSame('company', Tools::singularize('company'));
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
		$this->assertSame("0th", Tools::ordinalize(0));
		$this->assertSame("1st", Tools::ordinalize(1));
		$this->assertSame("2nd", Tools::ordinalize(2));
		$this->assertSame("3rd", Tools::ordinalize(3));
		$this->assertSame("4th", Tools::ordinalize(4));
		$this->assertSame("5th", Tools::ordinalize(5));
		$this->assertSame("10th", Tools::ordinalize(10));
		$this->assertSame("11th", Tools::ordinalize(11));
		$this->assertSame("12th", Tools::ordinalize(12));
		$this->assertSame("13th", Tools::ordinalize(13));
		$this->assertSame("14th", Tools::ordinalize(14));
		$this->assertSame("20th", Tools::ordinalize(20));
		$this->assertSame("21st", Tools::ordinalize(21));
		$this->assertSame("22nd", Tools::ordinalize(22));
		$this->assertSame("23rd", Tools::ordinalize(23));
		$this->assertSame("24th", Tools::ordinalize(24));
		$this->assertSame("25th", Tools::ordinalize(25));
		$this->assertSame("100th", Tools::ordinalize(100));
		$this->assertSame("101st", Tools::ordinalize(101));
		$this->assertSame("102nd", Tools::ordinalize(102));
		$this->assertSame("103rd", Tools::ordinalize(103));
		$this->assertSame("104th", Tools::ordinalize(104));
		$this->assertSame("105th", Tools::ordinalize(105));
		$this->assertSame("1000th", Tools::ordinalize(1000));
		$this->assertSame("1001st", Tools::ordinalize(1001));
		$this->assertSame("1002nd", Tools::ordinalize(1002));
		$this->assertSame("1003rd", Tools::ordinalize(1003));
		$this->assertSame("1004th", Tools::ordinalize(1004));
		$this->assertSame("1005th", Tools::ordinalize(1005));
	}

	public function testCamelize()
	{
		$this->assertSame('ActiveMapper', Tools::camelize('active_mapper', TRUE));
		$this->assertSame('NetteFramework', Tools::camelize('nette_framework', TRUE));
		$this->assertSame('Dibi', Tools::camelize('dibi', TRUE));
		$this->assertSame('activeMapper', Tools::camelize('active_mapper'));
		$this->assertSame('netteFramework', Tools::camelize('nette_framework'));
		$this->assertSame('dibi', Tools::camelize('dibi'));
	}

	public function testUnderscore1()
	{
		$this->assertSame('active_mapper', Tools::underscore('ActiveMapper'));
		$this->assertSame('nette_framework', Tools::underscore('NetteFramework'));
		$this->assertSame('dibi', Tools::underscore('dibi'));
		$this->assertSame('dibi', Tools::underscore('Dibi'));
	}
}