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
	protected static $metaData = array();
	/** @var array */
	protected $data;
	/** @var array */
	private $originalData;
	/** @var array */
	private $assocData;
	/** @var array */
	private $assocKeys;

	/**
	 * Get entity name
	 *
	 * @return string
	 */
	public static function getEntityName()
	{
		if (!isset(self::$metaData[get_called_class()]))
			self::$metaData[get_called_class()] = new EntityMetadata(get_called_class());

		return self::$metaData[get_called_class()]->name;
	}
	
	/**
	 * Get table name
	 * 
	 * @return string
	 */
	public static function getTableName()
	{
		if (!isset(self::$metaData[get_called_class()]))
			self::$metaData[get_called_class()] = new EntityMetadata(get_called_class());

		return self::$metaData[get_called_class()]->tableName;
	}
	
	/**
	 * Has primary key
	 * 
	 * @return bool
	 */
	public static function hasPrimaryKey()
	{
		if (!isset(self::$metaData[get_called_class()]))
			self::$metaData[get_called_class()] = new EntityMetadata(get_called_class());
		
		return self::$metaData[get_called_class()]->hasPrimaryKey();
	}
	
	/**
	 * Get primary key name
	 * 
	 * @return string|null
	 */
	public static function getPrimaryKey()
	{
		if (!isset(self::$metaData[get_called_class()]))
			self::$metaData[get_called_class()] = new EntityMetadata(get_called_class());
		
		return self::$metaData[get_called_class()]->getPrimaryKey();
	}

	/**
	 * Get columns meta data
	 *
	 * @return array|null
	 */
	public static function getColumnsMetaData()
	{
		if (!isset(self::$metaData[get_called_class()]))
			self::$metaData[get_called_class()] = new EntityMetadata(get_called_class());

		return self::$metaData[get_called_class()]->columns;
	}
	
	/**
	 * Has column mapped
	 * 
	 * @return bool
	 */
	public static function hasColumnMetaData($name)
	{
		if (!isset(self::$metaData[get_called_class()]))
			self::$metaData[get_called_class()] = new EntityMetadata(get_called_class());

		return self::$metaData[get_called_class()]->hasColumn($name);
	}
	
	/**
	 * Get mapped column
	 * 
	 * @return ActiveMapper\IDataType
	 */
	public static function getColumnMetaData($name)
	{
		if (!isset(self::$metaData[get_called_class()]))
			self::$metaData[get_called_class()] = new EntityMetadata(get_called_class());

		return self::$metaData[get_called_class()]->getColumn($name);
	}
	
	/**
	 * Get associations meta data
	 *
	 * @return array|null
	 */
	public static function getAssociationsMetaData()
	{
		if (!isset(self::$metaData[get_called_class()]))
			self::$metaData[get_called_class()] = new EntityMetadata(get_called_class());

		return self::$metaData[get_called_class()]->associations;
	}

	/********************************************************** Columns ***************************************************************p*v*/

	/**
	 * Contstuctor
	 *
	 * @param array $data
	 */
	public function __construct(array $data)
	{
		$class = get_called_class();

		if (!isset(self::$metaData[$class]))
			self::$metaData[$class] = new EntityMetadata($class);
		
		$this->assocData = array();
		if (!empty(self::$metaData[$class]->columns))
		{
			$this->data = array();
			foreach (self::$metaData[$class]->columns as $column)
			{
				if (self::$metaData[$class]->hasPrimaryKey() && isset($data[self::$metaData[$class]->getPrimaryKey()]))
				{
					if (isset($data[$column->name]))
						$this->originalData[$column->name] = $column->sanitize($data[$column->name]);
					else
					{
						$this->originalData[$column->name] = new LazyLoad(
							$class, $column->name, $data[self::$metaData[$class]->getPrimaryKey()]);
					}
				}
				else
					$this->originalData[$column->name] = $column->sanitize(isset($data[$column->name]) ? $data[$column->name] : NULL);
			}
		}
		if (!empty(self::$metaData[$class]->associations))
		{
			$this->assocKeys = array();
			$this->data = array();
			foreach (self::$metaData[$class]->associations as $association)
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
		if (self::$metaData[$class]->hasColumn($name))
		{
			if (isset($this->data[$name]))
				return $this->data[$name];
			else
			{
				if ($this->originalData[$name] instanceof LazyLoad)
				{
					$this->originalData[$name] = self::$metaData[$class]->columns[$name]->sanitize($this->originalData[$name]->data);
					return $this->originalData[$name];
				}
				else
					return $this->originalData[$name];
			}
				
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
		if (self::$metaData[$class]->hasColumn($name))
           	return $this->data[$name] = self::$metaData[$class]->getColumn($name)->sanitize($value);
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
		if (self::$metaData[$class]->hasAssociation($name))
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
		$assoc = self::$metaData[get_called_class()]->getAssociation($name);
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