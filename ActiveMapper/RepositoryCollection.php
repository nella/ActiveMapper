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

use dibi;

/**
 * Repository collection
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 */
class RepositoryCollection extends Collection
{
	/** @var DibiFuent */
	private $fluent;
	/** @var string */
	private $entity;

	/**
	 * Contstuctor
	 *
	 * @param string $entity
	 * @param DibiFluent $fluent
	 */
	public function __construct($entity, \DibiFluent $fluent = NULL)
	{
		if (!class_exists($entity) || !\Nette\Reflection\ClassReflection::from($entity)->implementsInterface('ActiveMapper\IEntity'))
			throw new \InvalidArgumentException("Entity [".$entity."] must implements 'ActiveMapper\\IEntity'");
		$this->entity = $entity;
		
		if ($fluent !== NULL)
			$this->fluent = $fluent;
		else
			$this->fluent = dibi::select("*")->from($entity::getTableName());
   	}

	/**
	 * Convert this collection to fluent
	 *
	 * @return DibiFluent
	 */
	public function toFluent()
	{
		return $this->fluent;
	}

	/********************************************************* Fluent methods *********************************************************p*v*/

	/**
	 * Replace selected columns
	 *
	 * @param array|mixed $columns
	 * @return ActiveMapper\RepositoryCollection
	 * @throws InvalidArgumentException
	 * @throws NotImplementedException
	 */
	public function select()
	{
		$columns = func_get_args();
		if (isset($columns[0]) && is_array($columns[0]))
			$columns = $columns[0];

		$entity = $this->entity;
		if (!$entity::hasPrimaryKey())
			throw new \NotImplementedException("Lazy load for entity without primary key not supported");

		foreach($columns as $column)
		{
			if (!$entity::hasColumn($column))
				throw new \InvalidArgumentException("Column '".$column."' must be valid '".$entity."' column");
		}

		$this->fluent->removeClause('select');
		$this->fluent->select(array_unique(array_merge(array($entity::getPrimaryKey()), $columns)));

		return $this;
	}

	/**
	 * Add where
	 *
	 * @param string $column entity column name
	 * @param string $value
	 * @return ActiveMapper\RepositoryCollection
	 */
	public function where($column, $value)
	{
		$this->fluent->where("[".Tools::underscore($column)."] = ".Repository::getModificator($this->entity, $column), $value);

      	return $this;
	}

	/**
	 * Add where not
	 *
	 * @param string $column entity column name
	 * @param string $value
	 * @return ActiveMapper\RepositoryCollection
	 */
	public function whereNot($column, $value)
	{
		$this->fluent->where("[".Tools::underscore($column)."] != ".Repository::getModificator($this->entity, $column), $value);

      	return $this;
	}

	/**
	 * Add where like
	 *
	 * @param string $column entity column name
	 * @param string $value
	 * @return ActiveMapper\RepositoryCollection
	 */
	public function whereLike($column, $value)
	{
		$this->fluent->where("[".Tools::underscore($column)."] LIKE ".Repository::getModificator($this->entity, $column), $value);

      	return $this;
	}

	/**
	 * Add where not like
	 *
	 * @param string $column entity column name
	 * @param string $value
	 * @return ActiveMapper\RepositoryCollection
	 */
	public function whereNotLike($column, $value)
	{
		$this->fluent->where("[".Tools::underscore($column)."] NOT LIKE ".Repository::getModificator($this->entity, $column), $value);

      	return $this;
	}

	/**
	 * Add where array
	 *
	 * @param string $column entity column name
	 * @param array $value
	 * @return ActiveMapper\RepositoryCollection
	 */
	public function whereIn($column, $value)
	{
		$this->fluent->where("[".Tools::underscore($column)."] = ".Repository::getModificator($this->entity, $column), $value);

      	return $this;
	}

	/**
	 * Count
	 *
	 * @return int
	 */
	public function count()
	{
		if (!$this->isFrozen())
		{
			$this->freeze();
			$this->data = $this->fluent->execute()->setRowClass($this->entity)->fetchAll();
		}

		return count($this->data);
   	}

	/**
	 * Fetch all
	 *
	 * @param int $offset
	 * @param int $limit
	 * @return ActiveMapper\RepositoryCollection
	 * @throws InvalidStateException
	 */
	public function fetchAll($offset = NULL, $limit = NULL)
	{
		if (!$this->isFrozen())
		{
			$this->freeze();
			if (!empty($offset))
				$this->fluent->offset($offset);
			if (!empty($limit))
				$this->fluent->limit($limit);
			$this->data = $this->fluent->execute()->setRowClass($this->entity)->fetchAll($offset, $limit);
			return $this;
		}
		else
			throw new \InvalidStateException("This collection already fetched data");
	}
	
	/**
	 * Offset exists
	 *
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		if (!$this->isFrozen())
			$this->fetchAll();
		
		return parent::offsetGet($offset);
   	}

	/**
	 * Offset get
	 *
	 * @param int $offset
	 * @return ActiveMapper\IEntity|NULL
	 */
	public function offsetGet($offset)
	{
		if (!$this->isFrozen())
			$this->fetchAll();
		
		return parent::offsetGet($offset);
   	}
   	
   	/**
   	 * Get iterator
   	 * 
   	 * @return ArrayIterator
   	 */
   	public function getIterator()
   	{
   		if (!$this->isFrozen())
			$this->fetchAll();
		
		return parent::getIterator();
   	}
}