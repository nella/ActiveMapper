<?php
/**
 * ActiveMapper
 *
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @license    http://nellacms.com/license  New BSD License
 * @link       http://addons.nettephp.com/cs/active-mapper
 * @category   ActiveMapper
 * @package    ActiveMapper\Associations
 */

namespace ActiveMapper\Associations;

use ActiveMapper\Tools;

/**
 * Many to many entity association
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper\Associations
 * @property-read string $sourceEntity source entity class
 * @property-read string $targetEntity target entity class
 * @property-read string $name association name
 * @property-read string $sourceColumn source column name
 * @property-read string $targetColumn target column name
 * @property-read string $joinTable join table name
 * @property-read string $joinTableSourceColumn join table source column name
 * @property-read string $joinTableTargetColumn join table target column name
 */
class ManyToMany extends Base implements IAssociation
{
	/** @var bool */
	private $mapped;
	/** @var string */
	private $name;
	/** @var string */
	private $targetColumn;
	/** @var string */
	private $sourceColumn;
	/** @var string */
	private $joinTable;
	/** @var string */
	private $joinTableTargetColumn;
	/** @var string */
	private $joinTableSourceColumn;
	
	/**
	 * Costructor
	 *
	 * @param string $sourceEntity valid source entity class
	 * @param string $targetEntity valid target entity class
	 * @param bool $mapped is this association mapped/inversed
	 * @param string $name
	 * @param string $sourceColumn valid source column name
	 * @param string $targetColumn valid target column name
	 * @param string $joinTable join table name
	 * @param string $joinTableTargetColumn valid join table target column name
	 * @param string $joinTableSourceColumn valid join table source column name
	 * @throws InvalidArgumentException
	 */
	public function __construct(
		$sourceEntity, $targetEntity, $mapped = TRUE, $name = NULL, $sourceColumn = NULL, $targetColumn = NULL, $joinTable = NULL,
		$joinTableTargetColumn = NULL, $joinTableSourceColumn = NULL
	)
	{
		parent::__construct($sourceEntity, $targetEntity);
		
		$this->mapped = $mapped;
		
		if (empty($name))
			$this->name = lcfirst(Tools::pluralize(substr($targetEntity, strrpos($targetEntity, '\\') + 1)));
		else
			$this->name = $name;
		
		
		if (empty($sourceEntity) && !$sourceEntity::hasPrimaryKey())
		{
			throw new \InvalidArgumentException(
				"Must specifi source column, because entity '".$sourceEntity."' has not set PRIMARY KEY"
			);
		}
		
		if (empty($sourceColumn))
			$this->sourceColumn = Tools::underscore($sourceEntity::getPrimaryKey());
		elseif ($sourceEntity::hasMappedColumn($sourceColumn))
			$this->sourceColumn = $sourceColumn;
		else
			throw new \InvalidArgumentException("Source column '".$sourceColumn."' is not valid column '".$sourceEntity."' entity.");
		
		if (empty($targetColumn) && !$targetEntity::hasPrimaryKey())
		{
			throw new \InvalidArgumentException(
				"Must specifi source column, because entity '".$targetEntity."' has not set PRIMARY KEY"
			);
		}
		
		if (empty($targetColumn))
			$this->targetColumn = Tools::underscore($targetEntity::getPrimaryKey());
		elseif ($targetEntity::hasMappedColumn($targetColumn))
			$this->targetColumn = $targetColumn;
		else
			throw new \InvalidArgumentException("Source column '".$targetColumn."' is not valid column '".$targetEntity."' entity.");
		
		
		if (empty($joinTableSourceColumn))
		{
			$this->joinTableSourceColumn = Tools::underscore(
				substr($sourceEntity, strrpos($sourceEntity, '\\') + 1) . ucfirst($this->sourceColumn)
			);
		}
		else
			$this->joinTableSourceColumn = $joinTableSourceColumn;
		
		if (empty($joinTableTargetColumn))
		{
			$this->joinTableTargetColumn = Tools::underscore(
				substr($targetEntity, strrpos($targetEntity, '\\') + 1) . ucfirst($this->targetColumn)
			);
		}
		else
			$this->joinTableTargetColumn = $joinTableTargetColumn;
		
		
		if (!empty($joinTable))
			$this->joinTable = $joinTable;
		elseif ($this->mapped)
		{
			$this->joinTable = Tools::underscore(
				Tools::pluralize(substr($sourceEntity, strrpos($sourceEntity, '\\') + 1)) 
				. ucfirst(Tools::pluralize(substr($targetEntity, strrpos($targetEntity, '\\') + 1)))
			);
		}
		else
		{
			$this->joinTable = Tools::underscore(
				Tools::pluralize(substr($targetEntity, strrpos($targetEntity, '\\') + 1)) 
				. ucfirst(Tools::pluralize(substr($sourceEntity, strrpos($sourceEntity, '\\') + 1)))
			);
		}
	}
	
	/**
	 * Get name
	 * 
	 * @return string
	 */
	final public function getName()
	{
		return $this->name;
	}
	
	/**
	 * Get source column
	 * 
	 * @return string
	 */
	final public function getSourceColumn()
	{
		return $this->sourceColumn;
	}
	
	/**
	 * Get target column
	 * 
	 * @return string
	 */
	final public function getTargetColumn()
	{
		return $this->targetColumn;
	}
	
	/**
	 * Get join table
	 * 
	 * @return string
	 */
	final public function getJoinTable()
	{
		return $this->joinTable;
	}
	
	/**
	 * Get join table source column
	 * 
	 * @return string
	 */
	final public function getJoinTableSourceColumn()
	{
		return $this->joinTableSourceColumn;
	}
	
	/**
	 * Get join table target column
	 * 
	 * @return string
	 */
	final public function getJoinTableTargetColumn()
	{
		return $this->joinTableTargetColumn;
	}
	
	/**
	 * Is association mapped
	 * - FALSE inverset
	 * 
	 * @return bool
	 */
	final public function isMapped()
	{
		return $this->mapped;
	}
	
	/**
	 * Get association data
	 * 
	 * @package mixed $assocKey
	 * @return ActiveMapper\RepositoryCollection
	 */
	public function getData($assocKey)
	{	
		$entity = $this->targetEntity;
		$sourceEntity = $this->sourceEntity;
		return new \ActiveMapper\RepositoryCollection($entity, \dibi::select("[".$entity::getTableName()."].*")
			->from($entity::getTableName())->innerJoin($this->joinTable)->on("[".$this->joinTable."].[".$this->joinTableSourceColumn."] = "
				.\ActiveMapper\Repository::getModificator($this->sourceEntity, $this->sourceColumn), $assocKey)
			->and("[".$this->joinTable."].[".$this->joinTableTargetColumn."] = [".$entity::getTableName()."].[".$this->targetColumn."]"));
	}
}