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
	protected $_changedData;
	/** @var array */
	protected $_originalData;
	/** @var array */
	private $_assocData;
	/** @var array */
	private $_assocKeys;

	/**
	 * Contstuctor
	 *
	 * @param array $data
	 */
	public function __construct(array $data)
	{
		$metaData = Manager::getEntityMetaData(get_called_class());

		$this->_changedData = $this->_assocData = $this->_assocKeys = array();
		if (count($data) >= 0) {
			if (!isset($data[$metaData->primaryKey]))
				throw new \InvalidArgumentException("Data for entity '".$metaData->name."' must be load primary key");

			foreach ($metaData->columns as $column) {
				$name = Tools::underscore($column->name);
				if (array_key_exists($name, $data))
					$this->_originalData[$column->name] = $column->sanitize($data[$name]);
				else {
					$this->_originalData[$column->name] = new LazyLoad(get_called_class(), $name,
						Tools::underscore($data[$metaData->primaryKey]));
				}
			}
			if (count($metaData->associationsKeys) > 0 ) {
				foreach ($metaData->associationsKeys as $key) {
					if (!isset($data[$key]) && $metaData->hasPrimaryKey() && isset($data[$metaData->primaryKey]))
						throw \InvalidStateException("Association key '".$key."' must loaded for '".get_called_class ()."' entity.");

					$this->_assocKeys[$key] = $data[$key];
				}
			}
		}
	}

	/**
	 * Get changed data
	 *
	 * @return array
	 */
	public function getChangedData()
	{
		return $this->_changedData;
	}

	/**
	 * Get original data
	 *
	 * @return array
	 */
	public function getOriginalData()
	{
		return $this->_originalData;
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
		if ($metaData->hasColumn($name)) {
			if (isset($this->_changedData[$name]))
				return $this->_changedData[$name];
			else {
				if ($this->_originalData[$name] instanceof LazyLoad) {
					$this->_originalData[$name] = $metaData->getColumn($name)->sanitize($this->_originalData[$name]->data);
					return $this->_originalData[$name];
				} else
					return $this->_originalData[$name];
			}
		} else
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
           	return $this->_changedData[$name] = Manager::getEntityMetaData(get_called_class())->getColumn($name)->sanitize($value);
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
		if (Manager::getEntityMetaData(get_called_class())->hasAssociation($name)) {
			if (!isset($this->_assocData[$name]))
				$this->loadAssociationData($name);
				
			return $this->_assocData[$name];
		} else
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
			$this->_assocData[$name] = $assoc->getData($this->_assocKeys[$assoc->sourceColumn]);
		else
			$this->_assocData[$name] = $assoc->getData($this->_originalData[$assoc->sourceColumn]);
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