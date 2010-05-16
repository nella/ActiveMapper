<?php
/**
 * ActiveMapper
 *
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @license    http://nellacms.com/license  New BSD License
 * @link       http://addons.nettephp.com/cs/active-mapper
 * @category   ActiveMapper
 * @package    ActiveMapper
 */

namespace ActiveMapper;

use Nette\Reflection\ClassReflection;

/**
 * Data entity object
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 */
abstract class Entity extends \Nette\Object implements IEntity
{
	/** @var array */
	protected static $map = array();
	/** @var array */
	protected static $tableName = array();
	/** @var array */
	protected $data;
	/** @var array */
	private $originalData;
	/** @var array */
	private $assocData;
	/** @var array */
	private $assocKeys;

	/**
	 * Load properties map
	 *
	 * @return void
	 * @throws ActiveMapper\InvalidDataTypeException
	 * @throws NotImplementedException
	 */
	protected static function loadColumnsMap()
	{
		$class = get_called_class();
		if (!isset(self::$map[$class]))
			self::$map[$class] = array();
			
		foreach (ClassReflection::from($class)->getProperties() as $property)
		{
			if ($property->hasAnnotation('column'))
			{
				$map = &self::$map[$class]['columns'];
				if (!is_array($map))
					$map = array();
				
				$annotation = (array)$property->getAnnotation('column');

				if (!class_exists('ActiveMapper\DataTypes\\' . $annotation[0]))
					throw new \ActiveMapper\InvalidDataTypeException("Data type '" . $annotation[0] . "' not exist");
				$ref = ClassReflection::from('ActiveMapper\DataTypes\\' . $annotation[0]);

				unset($annotation[0]);
				$params = array_merge(array($property->name, $property->hasAnnotation('null')), $annotation);

				$map[$property->name] = call_user_func_array(array($ref, 'newInstance'), $params);
			}
			if ($property->hasAnnotation('primary'))
			{
				if (!isset(self::$map[$class]['primary']))
					self::$map[$class]['primary'] = $property->name;
				else
					throw new \NotImplementedException("Multiple column primary key not implemented [".$class."]");
			}
			if ($property->hasAnnotation('autoincrement')) /** @todo complete this */
			{
				if (!(self::$map[$class]['columns'][$property->name] instanceof \ActiveMapper\DataTypes\Int))
				{
					throw new \ActiveMapper\InvalidDataTypeException(
						"Autoincrement avaiable only for Int data type column [".$property->name."]"
					);
				}
			}
		}
	}

	/**
	 * Load associations map
	 *
	 * @return void
	 */
	protected static function loadAssociationsMap()
	{
		$class = get_called_class();
		$entityRef = new ClassReflection($class);
		if (!isset(self::$map[$class]))
			self::$map[$class] = array();
		if (!isset(self::$map[$class]['columns']))
			static::loadColumnsMap();
		
		$annotations = $entityRef->getAnnotations();
		if (isset($annotations['OneToOne']) && count($annotations['OneToOne']) > 0)
		{
			$map = &self::$map[$class]['associations'];
			if (!is_array($map))
				$map = array();
			
			foreach ($annotations['OneToOne'] as $data)
			{
				$data = (array)$data;
				$assoc = new Associations\OneToOne($class, $data[0],
					isset($data['mapped']) ? $data['mapped'] : TRUE,
					isset($data['name']) ? $data['name'] : NULL,
					isset($data['targetColumn']) ? $data['targetColumn'] : NULL,
					isset($data['sourceColumn']) ? $data['sourceColumn'] : NULL
				);
				$map[$assoc->name] = $assoc;
			}
		}
		if (isset($annotations['OneToMany']) && count($annotations['OneToMany']) > 0)
		{
			$map = &self::$map[$class]['associations'];
			if (!is_array($map))
				$map = array();
			
			foreach ($annotations['OneToMany'] as $data)
			{
				$data = (array)$data;
				$assoc = new Associations\OneToMany($class, $data[0],
					isset($data['name']) ? $data['name'] : NULL,
					isset($data['targetColumn']) ? $data['targetColumn'] : NULL,
					isset($data['sourceColumn']) ? $data['sourceColumn'] : NULL
				);
				$map[$assoc->name] = $assoc;
			}
		}
		if (isset($annotations['ManyToOne']) && count($annotations['ManyToOne']) > 0)
		{
			$map = &self::$map[$class]['associations'];
			if (!is_array($map))
				$map = array();
			
			foreach ($annotations['ManyToOne'] as $data)
			{
				$data = (array)$data;
				$assoc = new Associations\ManyToOne($class, $data[0],
					isset($data['name']) ? $data['name'] : NULL,
					isset($data['targetColumn']) ? $data['targetColumn'] : NULL,
					isset($data['sourceColumn']) ? $data['sourceColumn'] : NULL
				);
				$map[$assoc->name] = $assoc;
			}
		}
		if (isset($annotations['ManyToMany']) && count($annotations['ManyToMany']) > 0)
		{
			$map = &self::$map[$class]['associations'];
			if (!is_array($map))
				$map = array();
			
			foreach ($annotations['ManyToMany'] as $data)
			{
				$data = (array)$data;
				$assoc = new Associations\ManyToMany($class, $data[0],
					isset($data['mapped']) ? $data['mapped'] : TRUE,
					isset($data['name']) ? $data['name'] : NULL,
					isset($data['targetColumn']) ? $data['targetColumn'] : NULL,
					isset($data['sourceColumn']) ? $data['sourceColumn'] : NULL,
					isset($data['joinTable']) ? $data['joinTable'] : NULL,
					isset($data['joinTableTargetColumn']) ? $data['joinTableTargetColumn'] : NULL,
					isset($data['joinTableSourceColumn']) ? $data['joinTableSourceColumn'] : NULL
				);
				$map[$assoc->name] = $assoc;
			}
		}
	}

	/**
	 * Get columns map
	 *
	 * @return array|null
	 */
	public static function getColumnsMap()
	{
		if (!isset(self::$map[get_called_class()]['columns']))
			self::loadColumnsMap();
		return isset(self::$map[get_called_class()]['columns']) ? self::$map[get_called_class()]['columns'] : NULL;
	}
	
	/**
	 * Get associations map
	 *
	 * @return array|null
	 */
	public static function getAssociationsMap()
	{
		if (!isset(self::$map[get_called_class()]['associations']))
			self::loadAssociationsMap();
		return isset(self::$map[get_called_class()]['associations']) ? self::$map[get_called_class()]['associations'] : NULL;
	}
	
	/**
	 * Get table name
	 * 
	 * @return string
	 */
	public static function getTableName()
	{
		if (!isset(self::$map[get_called_class()]['tableName']))
		{
			$ref = ClassReflection::from(get_called_class());
			if ($ref->hasAnnotation('tableName') && is_string($ref->getAnnotation('tableName')))
				self::$map[get_called_class()]['tableName'] = $ref->getAnnotation('tableName');
			else
			{
				self::$map[get_called_class()]['tableName'] = Tools::underscore(
					Tools::pluralize(substr(get_called_class(), strrpos(get_called_class(), '\\') + 1))
				);
			}
		}
		return self::$map[get_called_class()]['tableName'];
	}
	
	/**
	 * Has primary key
	 * 
	 * @return bool
	 */
	public static function hasPrimaryKey()
	{
		if (!isset(self::$map[get_called_class()]['columns']))
			self::loadColumnsMap();
		return isset(self::$map[get_called_class()]['primary']);
	}
	
	/**
	 * Get primary key name
	 * 
	 * @return string|null
	 */
	public static function getPrimaryKey()
	{
		return static::hasPrimaryKey() ? self::$map[get_called_class()]['primary'] : NULL;
	}
	
	/**
	 * Has column mapped
	 * 
	 * @return bool
	 */
	public static function hasMappedColumn($name)
	{
		if (!isset(self::$map[get_called_class()]['columns']))
			self::loadColumnsMap();
		if (isset(self::$map[get_called_class()]['columns']) && isset(self::$map[get_called_class()]['columns'][$name]))
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	 * Get mapped column
	 * 
	 * @return ActiveMapper\IDataType
	 */
	public static function getMappedColumn($name)
	{
		if (!static::hasMappedColumn($name))
			throw new \InvalidArgumentException("Column '".$name."' not exist in '".get_called_class()."' entity");
		
		return self::$map[get_called_class()]['columns'][$name];
	}

	/********************************************************** Columns ***************************************************************p*v*/

	/**
	 * Contstuctor
	 *
	 * @param array $data
	 */
	public function __construct(array $data)
	{
		if (!isset(self::$map[get_called_class()]['associations']))
			static::loadAssociationsMap();
		
		$this->assocData = array();
		if (isset(self::$map[get_called_class()]['columns']))
		{
			$this->data = array();
			foreach (self::$map[get_called_class()]['columns'] as $column)
			{
				// @TODO layzy loading with null
				$this->originalData[$column->name] = $column->sanitize(isset($data[$column->name]) ? $data[$column->name] : NULL);
			}
		}
		if (isset(self::$map[get_called_class()]['associations']))
		{
			$this->assocKeys = array();
			$this->data = array();
			foreach (self::$map[get_called_class()]['associations'] as $association)
			{
				if (!isset($this->originalData[$association->sourceColumn]) && isset($data[$association->sourceColumn]))
					$this->assocKeys[$association->sourceColumn] = $data[$association->sourceColumn];
			}
		}
	}

	/**
	 * Getter
	 *
	 * @param string $name
	 * @return mixed
	 * @throws MemberAccessException
	 */
	public function &__get($name)
	{
		try {
			return $this->universalGetValue($name);
		} catch (\MemberAccessException $e) {
			return parent::__get($name);
		}
	}

	/**
	 * Universal value getter
	 *
	 * @param string $name
	 * @return mixed
	 * @throws MemberAccessException
	 */
	private function &universalGetValue($name)
	{
		$class = get_called_class();
		if (isset(self::$map[$class]['columns']) && array_key_exists($name, self::$map[$class]['columns']))
		{
			if (isset($this->data[$name]))
				return $this->data[$name];
			else
				return $this->originalData[$name];
		}
		else
			throw new \MemberAccessException("Cannot read to undeclared column " . $class . "::\$$name.");
	}

	/**
	 * Setter
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return mixed
	 * @throws MemberAccessException
	 */
	public function __set($name, $value)
	{
		try {
			return $this->universalSetValue($name, $value);
		} catch (\MemberAccessException $e) {
			return parent::__set($name, $value);
		}
	}

	/**
	 * Universal value setter
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return mixed
	 * @throws MemberAccessException
	 */
	private function universalSetValue($name, $value)
	{
		$class = get_called_class();
		if (isset(self::$map[$class]['columns']) && array_key_exists($name, self::$map[$class]['columns']))
           	return $this->data[$name] = self::$map[$class]['columns'][$name]->sanitize($value);
		else
			throw new \MemberAccessException("Cannot assign undeclared column " . $class . "::\$$name.");
	}

	/******************************************************** Associations ************************************************************p*v*/

	
	/**
	 * Method overload for associations
	 * 
	 * @param string $name associtation name
	 * @param array $args
	 * @return mixed
	 * @throws MemberAccessException
	 */
	public function __call($name, $args)
	{
		try {
			return $this->universalGetAssociation($name);
		} catch (\MemberAccessException $e) {
			return parent::__call($name, $args);
		}
	}
	
	/**
	 * Universal association getter
	 * 
	 * @param string $name associtation name
	 * @return ActiveMapper\IEntity|ActiveMapper\Collection
	 * @throws MemberAccessException
	 */
	private function &universalGetAssociation($name)
	{
		$class = get_called_class();
		if (isset(self::$map[$class]['associations']) && array_key_exists($name, self::$map[$class]['associations']))
		{
			if (!isset($this->assocData[$name]))
				$this->loadAssociationData($name);
				
			return $this->assocData[$name];
		}
		else
			throw new \MemberAccessException("Cannot read undeclared association " . $class . "::\$$name.");
	}
	
	/**
	 * Load association data
	 * 
	 * @param string $name association name
	 * @return void
	 */
	private function loadAssociationData($name)
	{
		$assoc = self::$map[get_called_class()]['associations'][$name];
		if (($assoc instanceof \ActiveMapper\Associations\OneToOne && 
			!$assoc->isMapped()) || $assoc instanceof \ActiveMapper\Associations\ManyToOne
		)
			$this->assocData[$name] = $assoc->getData($this->assocKeys[$assoc->sourceColumn]);
		else
			$this->assocData[$name] = $assoc->getData($this->originalData[$assoc->sourceColumn]);
	}
	
	/**
	 * Invoke
	 *
	 * @param string $name association name
	 * @return mixed
	 *
	public function __invoke($name)
	{
		$class = get_called_class();
		if (isset(self::$map[$class]['associations']) && array_key_exists($name, self::$map[$class]['associations']))
		{
			if (isset($this->assocData[$name]))
				$this->loadAssociationData($name);
				
			return $this->assocData[$name];
		}
		else
			throw new \MemberAccessException("Cannot read undeclared association " . $class . "::\$$name.");
	}*/
}