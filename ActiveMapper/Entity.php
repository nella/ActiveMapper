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
	protected $data;
	/** @var array */
	private $originalData;
	/** @var array */
	private $assocData;
	/** @var array */
	private $assocKeys;

	/**
	 * Contstuctor
	 *
	 * @param array $data
	 */
	public function __construct(array $data)
	{
		$metaData = Manager::getEntityMetaData(get_called_class());

		$this->data = $this->assocData = $this->assocKeys = array();
		foreach ($metaData->columns as $column)
		{
			if ($metaData->hasPrimaryKey() && isset($data[$metaData->primaryKey]))
			{
				if (isset($data[$column->name]))
					$this->originalData[$column->name] = $column->sanitize($data[$column->name]);
				else
				{
					$this->originalData[$column->name] = new LazyLoad(get_called_class(), $column->name, $data[$metaData->primaryKey]);
				}
			}
			else
				$this->originalData[$column->name] = $column->sanitize(isset($data[$column->name]) ? $data[$column->name] : NULL);
		}
		if (count($metaData->associationsKeys) > 0 )
		{
			foreach ($metaData->associationsKeys as $key)
			{
				if (!isset($data[$key]) && $metaData->hasPrimaryKey() && isset($data[$metaData->primaryKey]))
					throw \InvalidStateException("Association key '".$key."' must loaded for '".get_called_class ()."' entity.");

				$this->assocKeys[$key] = $data[$key];
			}
		}
	}

	/********************************************************** Columns ***************************************************************p*v*/

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
		$metaData = Manager::getEntityMetaData(get_called_class());
		if ($metaData->hasColumn($name))
		{
			if (isset($this->data[$name]))
				return $this->data[$name];
			else
			{
				if ($this->originalData[$name] instanceof LazyLoad)
				{
					$this->originalData[$name] = $metaData->getColumn($name)->sanitize($this->originalData[$name]->data);
					return $this->originalData[$name];
				}
				else
					return $this->originalData[$name];
			}
				
		}
		else
			throw new \MemberAccessException("Cannot read to undeclared column " . get_called_class() . "::\$$name.");
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
		if (Manager::getEntityMetaData(get_called_class())->hasColumn($name))
           	return $this->data[$name] = Manager::getEntityMetaData(get_called_class())->getColumn($name)->sanitize($value);
		else
			throw new \MemberAccessException("Cannot assign undeclared column " . get_called_class() . "::\$$name.");
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
		if (Manager::getEntityMetaData(get_called_class())->hasAssociation($name))
		{
			if (!isset($this->assocData[$name]))
				$this->loadAssociationData($name);
				
			return $this->assocData[$name];
		}
		else
			throw new \MemberAccessException("Cannot read undeclared association " . get_called_class() . "::\$$name.");
	}
	
	/**
	 * Load association data
	 * 
	 * @param string $name association name
	 * @return void
	 */
	private function loadAssociationData($name)
	{
		$assoc = Manager::getEntityMetaData(get_called_class())->getAssociation($name);
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