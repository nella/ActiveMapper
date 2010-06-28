<?php
/**
 * ActiveMapper
 *
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @license    http://nellacms.com/license  New BSD License
 * @link       http://addons.nette.org/cs/active-mapper
 * @category   ActiveMapper
 * @package    ActiveMapper\Associations
 */

namespace ActiveMapper\Associations;

use ActiveMapper\Tools,
	ActiveMapper\Manager;

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
		$targetEntityMetaData = Manager::getEntityMetaData($targetEntity);
		$sourceEntityMetaData = Manager::getEntityMetaData($sourceEntity);
		
		if (empty($name))
			$this->name = lcfirst($targetEntityMetaData->name);
		else
			$this->name = $name;
		
		if (empty($targetColumn))
			$this->targetColumn = Tools::underscore($targetEntityMetaData->primaryKey);
		elseif ($targetEntityMetaData->hasColumn($targetColumn))
			$this->targetColumn = $targetColumn;
		else
			throw new \InvalidArgumentException("Target column '".$targetColumn."' is not valid column '".$targetEntity."' entity.");
		
		if (empty($sourceColumn))
			$this->sourceColumn = Tools::underscore($targetEntityMetaData->name.ucfirst($this->targetColumn));
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
		return \dibi::select("*")->from(Manager::getEntityMetaData($this->targetEntity)->tableName)
			->where("[".$this->targetColumn."] = "
				.\ActiveMapper\Manager::getRepository($this->targetEntity)->getModificator($this->targetColumn), $assocKey)
			->execute()->setRowClass($this->targetEntity)->fetch();
	}
}