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
	private $entityClass;

	/**
	 * Contstuctor
	 *
	 * @param string $entityClass
	 * @param DibiFluent $fluent
	 */
	public function __construct($entityClass, \DibiFluent $fluent = NULL)
	{
		if (!class_exists($entityClass) || !\Nette\Reflection\ClassReflection::from($entityClass)->implementsInterface('ActiveMapper\IEntity'))
			throw new \InvalidArgumentException("Entity [".$entityClass."] must implements 'ActiveMapper\\IEntity'");
		$this->entityClass = $entityClass;
		
		if ($fluent !== NULL)
			$this->fluent = $fluent;
		else
			$this->fluent = dibi::select("*")->from($this->getMetaData()->tableName);
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

		$selectColumns = array();
		foreach($columns as $column) {
			if (!$this->getMetaData()->hasColumn($column))
				throw new \InvalidArgumentException("Column '".$column."' must be valid '".$this->entityClass."' column");
			$selectColumns[] = "[".$this->getMetaData()->tableName."].[".$column."]";
		}
		foreach($this->getMetaData()->associationsKeys as $key)
			$selectColumns[] = "[".$this->getMetaData()->tableName."].[".$key."]";
			
		$selectColumns[] = "[".$this->getMetaData()->tableName."].[".$this->getMetaData()->primaryKey."]";
		$selectColumns = array_unique($selectColumns);

		callback($this->fluent->removeClause('select'), 'select')->invokeArgs($selectColumns);

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
		$this->fluent->where("[".Tools::underscore($column)."] = ".$this->getModificator($column), $value);

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
		$this->fluent->where("[".Tools::underscore($column)."] != ".$this->getModificator($column), $value);

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
		$this->fluent->where("[".Tools::underscore($column)."] LIKE ".$this->getModificator($column), $value);

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
		$this->fluent->where("[".Tools::underscore($column)."] NOT LIKE ".$this->getModificator($column), $value);

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
		$this->fluent->where("[".Tools::underscore($column)."] = ".$this->getModificator($column), $value);

      	return $this;
	}

	/**
	 * Count
	 *
	 * @return int
	 */
	public function count()
	{
		if (!$this->isFrozen()) {
			$this->freeze();
			$this->data = $this->fluent->execute()->setRowClass($this->entityClass)->fetchAll();
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
		if (!$this->isFrozen()) {
			$this->freeze();
			if (!empty($offset))
				$this->fluent->offset($offset);
			if (!empty($limit))
				$this->fluent->limit($limit);

			$res = $this->fluent->execute();
			$this->data = $res->setRowClass($this->entityClass)->fetchAll($offset, $limit);
			return $this;
		} else
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
   	
   	/**
   	 * Get modificator
   	 * 
   	 * @param string $entityClass
   	 * @param string $column entity column name
   	 * @return string
   	 */
   	private function getModificator($column)
   	{
		return Manager::getRepository($this->entityClass)->getModificator($column);
   	}
   	
   	/**
   	 * Get metadata
   	 * 
   	 * @return ActiveMapper\MetaData
   	 */
   	private function getMetaData()
   	{
		return Manager::getEntityMetaData($this->entityClass);
   	}
}