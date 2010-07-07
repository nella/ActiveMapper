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

/**
 * Identity map
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 */
class IdentityMap extends \Nette\Object
{
	/** @var ActiveMapper\Manager */
	protected $em;
	/** @var string */
	protected $entity;
	/** @var array */
	protected $data = array();
	/** @var array */
	protected $idReference = array();
	/** @var array */
	protected $originalData = array();

	/**
	 * Construct
	 *
	 * @param ActiveMapper\Manager $em
	 * @param string $entity full entity class name (with namespace)
	 */
	public function __construct(Manager $em, $entity)
	{
		// TODO: verify entity class

		$this->em = $em;
		$this->entity = $entity;
	}

	/**
	 * Is entity mapped
	 *
	 * @param mixed $entity
	 * @return bool
	 */
	public function isMapped(&$entity)
	{
		if ($this->entity != get_class($entity))
			throw new \InvalidArgumentException("Entity [".get_class($entity)."] is not valid '{$this->entity}' for this identity map.");

		return isset($this->data[spl_object_hash($entity)]);
	}

	/**
	 * Find entity by primary key
	 * 
	 * @param mixed $primaryKey
	 * @return NULL|mixed
	 */
	public function find($primaryKey)
	{
		return isset($this->idReference[$primaryKey]) ? $this->idReference[$primaryKey] : NULL;
	}

	/**
	 * Get entity primary key value
	 *
	 * @param mixed $entity
	 * @return mixed
	 */
	private function getEntityPrimaryKey(&$entity)
	{
		$ref = new \Nette\Reflection\PropertyReflection($this->entity, Metadata::getMetadata($this->entity)->primaryKey);
		$ref->setAccessible(TRUE);
		$pk = $ref->getValue($entity);
		$ref->setAccessible(FALSE);
		return $pk;
	}

	/**
	 * Store entity
	 *
	 * @param mixed $entity
	 */
	public function store(&$entity)
	{
		if (!$this->isMapped($entity)) {
			$this->data[spl_object_hash($entity)] = &$entity;
			$this->originalData[spl_object_hash($entity)] = Metadata::getMetadata(get_class($entity))->getValues($entity, FALSE);
			if (($id = $this->getEntityPrimaryKey($entity)) !== NULL)
				$this->idReference[$id] = &$entity;
		}

		return $entity;
	}

	/**
	 * Detach entity
	 *
	 * @param mixed $entity
	 */
	public function detach(&$entity)
	{
		if ($this->isMapped($entity)) {
			unset($this->idReference[$this->getEntityPrimaryKey($entity)]);
			unset($this->data[spl_object_hash($entity)]);
		}
	}

	/**
	 * Remap entity
	 *
	 * @param mixed $entity
	 */
	public function remap(&$entity)
	{
		if ($this->isMapped($entity)) {
			$metadata = Metadata::getMetadata(get_class($entity));
			$this->originalData[spl_object_hash($entity)] = $metadata->getValues($entity, FALSE);
			if (isset($this->originalData[spl_object_hash($entity)][$metadata->primaryKey]))
				unset($this->originalData[spl_object_hash($entity)][$metadata->primaryKey]);
		}
	}

	/**
	 * Map entity or entities
	 *
	 * @param array $input
	 * @return mixed|array
	 * @throws InvalidArgumentException
	 */
	public function map($input)
	{
		$metadata = Metadata::getMetadata($this->entity);
		if (is_array($input)
				&& count(array_filter($input, function ($item) { return is_array($item) || $item instanceof \ArrayAccess; }))) {
			$output = array();
			foreach ($input as $key => $row) {
				if (!isset($row[$metadata->primaryKey]))
					throw new \InvalidArgumentException("Data for entity '".$this->entity."' must be load primary key");
				if (isset($this->idReference[$row[Metadata::getMetadata($this->entity)->primaryKey]]))
					$output[$key] = &$this->idReference[$row[$metadata->primaryKey]];
				else {
					$tmp = $metadata->getInstance($this->em, $row);
					$this->idReference[$row[$metadata->primaryKey]] = &$tmp;
					$this->data[spl_object_hash($tmp)] = &$tmp;
					$this->originalData[spl_object_hash($tmp)] = (array)$row;
					if (isset($this->originalData[spl_object_hash($tmp)][$metadata->primaryKey]))
						unset($this->originalData[spl_object_hash($tmp)][$metadata->primaryKey]);
					$output[$key] = &$tmp;
					unset($tmp);
				}
			}
			
			return $output;
		} elseif (is_array($input) || $input instanceof \ArrayAccess) {
			if (!isset($input[$metadata->primaryKey]))
				throw new \InvalidArgumentException("Data for entity '".$this->entity."' must be load primary key");
			if (isset($this->idReference[$input[$metadata->primaryKey]]))
				return $this->idReference[$input[$metadata->primaryKey]];
			else {
				$tmp = $metadata->getInstance($this->em, $input);
				$this->idReference[$input[$metadata->primaryKey]] = &$tmp;
				$this->data[spl_object_hash($tmp)] = &$tmp;
				$this->originalData[spl_object_hash($tmp)] = (array)$input;
				if (isset($this->originalData[spl_object_hash($tmp)][$metadata->primaryKey]))
						unset($this->originalData[spl_object_hash($tmp)][$metadata->primaryKey]);
				return $tmp;
			}
		} else
			throw new \InvalidArgumentException("Map accept only loaded data or loaded data array");
	}

	/**
	 * Get saved values
	 *
	 * @param mixed $entity
	 * @return array
	 */
	public function getSavedValues($entity)
	{
		$metadata = Metadata::getMetadata(get_class($entity));
		if ($this->isMapped($entity)) {
			$data = $metadata->getValues($entity, FALSE);
			return array_diff($data, $this->originalData[spl_object_hash($entity)]);
		} else
			return $metadata->getValues($entity);
	}
}