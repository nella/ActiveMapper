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

use dibi;
use ActiveMapper\Tools;
use ActiveMapper\Repository;

/**
 * One to one entity association
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper\Associations
 * @property-read string $sourceEntity source entity class
 * @property-read string $targetEntity target entity class
 * @property-read string $sourceColumn source column name
 * @property-read string $targetColumn target column name
 */
class OneToOne extends Base implements IAssociation
{
	/** @var bool */
	private $mapped;
	/** @var string */
	private $name;
	/** @var string */
	private $targetColumn;
	/** @var string */
	private $sourceColumn;
	
	/**
	 * Costructor
	 *
	 * @param string $sourceEntity valid source entity class
	 * @param string $targetEntity valid target entity class
	 * @param bool $mapped is this association mapped/inversed
	 * @param string $name
	 * @param string $targetColumn valid targer column name
	 * @param string $sourceColumn valid source column name
	 * @throws InvalidArgumentException
	 */
	public function __construct($sourceEntity, $targetEntity, $mapped = TRUE, $name = NULL, $targetColumn = NULL, $sourceColumn = NULL)
	{
		parent::__construct($sourceEntity, $targetEntity);
		
		$this->mapped = $mapped;
		
		if (empty($name))
			$this->name = lcfirst($targetEntity::getEntityName());
		else
			$this->name = $name;
		
		if ($this->mapped)
		{
			if (empty($sourceEntity) && !$sourceEntity::hasPrimaryKey())
			{
				throw new \InvalidArgumentException(
					"Must specifi source column, because entity '".$sourceEntity."' has not set PRIMARY KEY"
				);
			}
			
			if (empty($sourceColumn))
				$this->sourceColumn = Tools::underscore($sourceEntity::getPrimaryKey());
			elseif ($sourceEntity::hasColumnMetaData($sourceColumn))
				$this->sourceColumn = $sourceColumn;
			else
				throw new \InvalidArgumentException("Source column '".$sourceColumn."' is not valid column '".$sourceEntity."' entity.");
			
			
			if (empty($targetColumn))
			{
				$this->targetColumn = Tools::underscore($sourceEntity::getEntityName().ucfirst($this->sourceColumn));
			}
			else
				$this->targetColumn = $targetColumn;
		}
		else
		{
			if (empty($targetEntity) && !$targetEntity::hasPrimaryKey())
			{
				throw new \InvalidArgumentException(
					"Must specifi source column, because entity '".$targetEntity."' has not set PRIMARY KEY"
				);
			}
			
			if (empty($targetColumn))
				$this->targetColumn = Tools::underscore($targetEntity::getPrimaryKey());
			elseif ($targetEntity::hasColumnMetaData($targetColumn))
				$this->targetColumn = $targetColumn;
			else
				throw new \InvalidArgumentException("Target column '".$targetColumn."' is not valid column '".$targetEntity."' entity.");
			
			
			if (empty($sourceColumn))
			{
				$this->sourceColumn = Tools::underscore($targetEntity::getEntityName().ucfirst($this->targetColumn));
			}
			else
				$this->sourceColumn = $sourceColumn;
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
	 * @return ActiveMapper\IEntity
	 */
	public function getData($assocKey)
	{	
		$entity = $this->targetEntity;
		if ($this->mapped)
		{
			return dibi::select("*")->from($entity::getTableName())
				->where("[".$this->targetColumn."] = ".Repository::getModificator($this->sourceEntity, $this->sourceColumn), $assocKey)
				->execute()->setRowClass($entity)->fetch();
		}
		else
		{
			return dibi::select("*")->from($entity::getTableName())
				->where("[".$this->targetColumn."] = ".Repository::getModificator($entity, $this->targetColumn), $assocKey)->execute()
				->setRowClass($entity)->fetch();
		}
	}
}