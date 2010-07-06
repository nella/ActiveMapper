<?php
/**
 * ActiveMapper
 *
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @license    http://nellacms.com/license  New BSD License
 * @link       http://addons.nette.org/cs/active-mapper
 * @category   ActiveMapper
 * @package    ActiveMapper
 */

namespace ActiveMapper;

use Nette\Reflection\ClassReflection,
	Nette\Reflection\PropertyReflection;

/**
 * Entity metada object
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 * @property-read string $tableName entity table name
 * @property-read array $columns entity columns array
 * @property-read string $primaryKey entity primary key name
 * @property-read string $name entity name (class without namespace)
 * @property-read bool $primaryKeyAutoincrement is entity primary key autoincrement
 */
class Metadata extends \Nette\Object
{
	/** @var array<ActiveMapper\Metadata> */
	private static $metadata = array();
	/** @var string */
	private $tableName;
	/** @var array<ActiveMapper\DataTypes\IDataType> */
	private $columns;
	/** @var string */
	private $primaryKey;
	/** @var bool */
	private $primaryKeyAutoincrement;
	/** @var string */
	private $entity;
	/** @var string */
	private $name;

	public function __construct($entity)
	{

		$ref = new ClassReflection($entity);
		// TODO: verify entity class

		$this->entity = $entity;
		$this->tableName = $this->primaryKey = NULL;
		$this->primaryKeyAutoincrement = FALSE;

		foreach ($ref->getProperties() as $property) {
			if ($property->hasAnnotation('column')) {
				$annotation = (array)$property->getAnnotation('column');
				$datatype = 'ActiveMapper\DataTypes\\'.$annotation[0];
				unset($annotation[0]);
				$params = array_merge(array($property->name, $property->hasAnnotation('null')), $annotation);

				if (!\class_exists($datatype))
					throw new \ActiveMapper\InvalidDataTypeException("Data type '" . $datatype . "' not exist");

				$this->columns[$property->name] = callback(ClassReflection::from($datatype), 'newInstance')->invokeArgs($params);
			}

			if ($property->hasAnnotation('primary')) {
				if ($property->hasAnnotation('column')) {
					if (empty($this->primaryKey))
						$this->primaryKey = $property->name;
					else
						throw new \NotImplementedException("Multiple column primary key not implemented [".$entity."]");
				} else
					throw new \NotImplementedException("Primary key must be column [".$entity."::".$property->name."]");
			}

			if ($property->hasAnnotation('autoincrement'))  {
				if (!($this->columns[$property->name] instanceof \ActiveMapper\DataTypes\Int)) {
					throw new \ActiveMapper\InvalidDataTypeException(
						"Autoincrement avaiable only for Int data type column [".$property->name."]"
					);
				} elseif ($property->name == $this->primaryKey)
					$this->primaryKeyAutoincrement = TRUE;
				else
					throw new \NotImplementedException("Auto increment for non primary key column not implemented");
			}
		}

		if (!$this->primaryKey)
			throw new \LogicException("Entity without primary key not supported");


		if ($pos = strrpos($entity, '\\'))
			$pos++;
		$this->name = substr($entity, $pos);

		if ($ref->hasAnnotation('tableName'))
			$this->tableName = $ref->getAnnotation('tableName');
		else
			$this->tableName = Tools::underscore(Tools::pluralize($this->name));
	}

	/**
	 * Get columns DataType objects
	 *
	 * @return array
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 * Has column
	 *
	 * @return bool
	 */
	public function hasColumn($name)
	{
		return isset($this->columns[$name]);
	}

	/**
	 * Get column DataType object by name
	 *
	 * @param string $name colum name
	 * @return ActiveMapper\DataTypes\IDataType
	 * @throws InvalidArgumentException
	 */
	public function getColumn($name)
	{
		if (!$this->hasColumn($name))
			throw new \InvalidArgumentException("Column '".$name."' not exist in '".$this->entity."' entity");

		return $this->columns[$name];
	}
	
	/**
	 * Get primary key name
	 * 
	 * @return string|null
	 */
	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}

	/**
	 * Is primary key autoincrement
	 *
	 * @return bool
	 */
	public function isPrimaryKeyAutoincrement()
	{
		return $this->primaryKeyAutoincrement;
	}

	/**
	 * Get primary key autoincrement
	 *
	 * @return bool
	 */
	public function getPrimaryKeyAutoincrement()
	{
		return $this->isPrimaryKeyAutoincrement();
	}

	/**
	 * Get entity table name
	 *
	 * @return string
	 */
	public function getTableName()
	{
		return $this->tableName;
	}

	/**
	 * Get entity name
	 *
	 * @return strin
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get metadata instance
	 *
	 * @param string $entity
	 * @return ActiveMapper\Metadata
	 * @throws InvalidArgumentException
	 * @throws NotImplementedException
	 * @throws ActiveMapper\InvalidDataTypeException
	 */
	public static function getMetadata($entity)
	{
		if (!isset(self::$metadata[$entity]))
			self::$metadata[$entity] = new static($entity);

		return self::$metadata[$entity];
	}

	/**
	 * Get entity instance with default data
	 *
	 * @param array $data
	 * @return mixed
	 */
	public function getInstance($data)
	{
		if (!is_array($data) && !($data instanceof \ArrayAccess))
			throw new \InvalidArgumentException("Get instance data must be array or implement ArrayAccess.");

		$ref = ClassReflection::from($this->entity);
		$instance = $ref->newInstance();
		foreach ($this->columns as $column) {
			$tmpName = Tools::underscore($column->name);
			if (isset($data[$tmpName])) {
				$prop = $ref->getProperty($column->name);
				$prop->setAccessible(TRUE);
				$prop->setValue($instance, $column->convertToPHPValue($data[$tmpName]));
				$prop->setAccessible(FALSE);
			}
		}
		
		return $instance;
	}

	/**
	 * Get entity primary key value
	 *
	 * @param mixed $entity
	 * @return mixed
	 */
	public function getPrimaryKeyValue(&$entity)
	{
		$ref = new PropertyReflection($this->entity, $this->primaryKey);
		$ref->setAccessible(TRUE);
		$primaryKey = $ref->getValue($entity);
		$ref->setAccessible(FALSE);
		return $primaryKey;
	}

	/**
	 * Set entity primary key value
	 *
	 * @param mixed $entity
	 * @param mixed $primaryKey
	 */
	public function setPrimaryKeyValue(&$entity, $primaryKey)
	{
		$ref = new PropertyReflection($this->entity, $this->primaryKey);
		$ref->setAccessible(TRUE);
		$ref->setValue($entity, $this->columns[$this->primaryKey]->convertToPHPValue($primaryKey));
		$ref->setAccessible(FALSE);
	}

	/**
	 * Get entity values
	 *
	 * @param mixed $entity
	 * @param bool $withPrimaryKey
	 * @return array
	 */
	public function getValues(&$entity, $withPrimaryKey = TRUE)
	{
		$data = array();
		foreach ($this->columns as $column) {
			if ($column->name == $this->primaryKey && !$withPrimaryKey)
				continue;
			
			$ref = new PropertyReflection($this->entity, $column->name);
			$ref->setAccessible(TRUE);
			$data[$column->name] = $ref->getValue($entity);
			$ref->setAccessible(FALSE);
		}

		return $data;
	}
}