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
 * Many to one entity association
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper\Associations
 * @property-read string $sourceEntity source entity class
 * @property-read string $targetEntity target entity class
 * @property-read string $name association name
 * @property-read string $sourceColumn source column name
 * @property-read string $targetColumn target column name
 */
class ManyToOne extends Base implements IAssociation
{
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
	 * @param string $name
	 * @param string $targetColumn valid targer column name
	 * @param string $sourceColumn valid source column name
	 * @throws InvalidArgumentException
	 */
	public function __construct($sourceEntity, $targetEntity, $name = NULL, $targetColumn = NULL, $sourceColumn = NULL)
	{
		parent::__construct($sourceEntity, $targetEntity);
		
		if (empty($name))
			$this->name = lcfirst($targetEntity::getEntityName());
		else
			$this->name = $name;
		
		if (empty($targetEntity) && !$targetEntity::hasPrimaryKey())
			throw new \InvalidArgumentException("Must specifi source column, because entity '".$targetEntity."' has not set PRIMARY KEY");
		
		if (empty($targetColumn))
			$this->targetColumn = Tools::underscore($targetEntity::getPrimaryKey());
		elseif ($targetEntity::hasColumnMetaData($targetColumn))
			$this->targetColumn = $targetColumn;
		else
			throw new \InvalidArgumentException("Target column '".$targetColumn."' is not valid column '".$targetEntity."' entity.");
		
		
		if (empty($sourceColumn))
			$this->sourceColumn = Tools::underscore($targetEntity::getEntityName().ucfirst($this->targetColumn));
		else
			$this->sourceColumn = $sourceColumn;
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
	 * Get association data
	 * 
	 * @package mixed $assocKey
	 * @return ActiveMapper\IEntity
	 */
	public function getData($assocKey)
	{	
		$entity = $this->targetEntity;
		return \dibi::select("*")->from($entity::getTableName())
			->where("[".$this->targetColumn."] = ".\ActiveMapper\Repository::getModificator($entity, $this->targetColumn), 
				$assocKey)->execute()->setRowClass($entity)->fetch();
	}
}