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

use dibi, 
	Nette\Reflection\ClassReflection;

/**
 * Identity map
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 */
class IdentityMap extends \Nette\Object
{
	/** @var string */
	protected $entity;
	/** @var array<ActiveMapper\IEntity> */
	protected $data = array();
	/** @var array<ActiveMapper\IEntity> */
	protected $idReference = array();

	/**
	 * Construct
	 * 
	 * @param string $entity full entity class name (with namespace)
	 */
	public function __construct($entity)
	{
		if (!class_exists($entity) || !ClassReflection::from($entity)->implementsInterface('ActiveMapper\IEntity'))
			throw new \InvalidArgumentException("Entity [".$entity."] must implements 'ActiveMapper\\IEntity'");

		$this->entity = $entity;
	}

	/**
	 * Is entity mapped
	 *
	 * @param ActiveMapper\IEntity $entity
	 * @return bool
	 */
	public function isMapped(IEntity $entity)
	{
		if ($this->entity != get_class($entity))
			throw new \InvalidArgumentException("Entity [".get_class($entity)."] is not valid '{$this->entity}' for this identity map.");

		return isset($this->data[spl_object_hash($entity)]);
	}

	/**
	 * Find entity by primary key
	 * 
	 * @param mixed $primaryKey
	 * @return NULL|ActiveMapper\IEntity
	 */
	public function find($primaryKey)
	{
		return isset($this->idReference[$primaryKey]) ? $this->idReference[$primaryKey] : NULL;
	}

	/**
	 * Store entity
	 *
	 * @param ActiveMapper\IEntity $entity
	 */
	public function store(IEntity &$entity)
	{
		if (!$this->isMapped($entity)) {
			$this->data[spl_object_hash($entity)] = &$entity;
			$this->idReference[$entity->{Manager::getEntityMetaData($this->entity)->primaryKey}] = &$entity;
		}

		return $entity;
	}

	/**
	 * Map entity or entities
	 *
	 * @param array $input
	 * @return ActiveMapper\IEntity|array
	 * @throws InvalidArgumentException
	 */
	public function &map($input)
	{
		if ((is_array($input) || $input instanceof \ArrayAccess)
				&& count(array_filter($input, function ($item) { return is_array($item) || $item instanceof \ArrayAccess; }))) {
			$output = array();
			foreach ($input as $key => $row) {
				if (!isset($row[Manager::getEntityMetaData($this->entity)->primaryKey]))
					throw new \InvalidArgumentException("Data for entity '".$this->entity."' must be load primary key");
				if (isset($this->idReference[$row[Manager::getEntityMetaData($this->entity)->primaryKey]]))
					$output[$key] = &$this->idReference[$row[Manager::getEntityMetaData($this->entity)->primaryKey]];
				else {
					$tmp = ClassReflection::from($this->entity)->newInstance($row);
					\Nette\Debug::dump($tmp);
					\Nette\Debug::dump($row);
					$this->idReference[$row[Manager::getEntityMetaData($this->entity)->primaryKey]] = &$tmp;
					$this->data[spl_object_hash($tmp)] = &$tmp;
					$output[$key] = &$tmp;
					unset($tmp);
				}
			}
			
			return $output;
		} elseif (is_array($input) || $input instanceof \ArrayAccess) {
			if (!isset($input[Manager::getEntityMetaData($this->entity)->primaryKey]))
				throw new \InvalidArgumentException("Data for entity '".$this->entity."' must be load primary key");
			if (isset($this->idReference[$input[Manager::getEntityMetaData($this->entity)->primaryKey]]))
				return $this->idReference[$input[Manager::getEntityMetaData($this->entity)->primaryKey]];
			else {
				$tmp = ClassReflection::from($this->entity)->newInstance($input);
				$this->idReference[$input[Manager::getEntityMetaData($this->entity)->primaryKey]] = &$tmp;
				$this->data[spl_object_hash($tmp)] = &$tmp;
				return $tmp;
			}
		} else
			throw new \InvalidArgumentException("Map accept only loaded data or loaded data array");
	}
}