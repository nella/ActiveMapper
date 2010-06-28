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

use ActiveMapper\Tools,
	ActiveMapper\Manager;

/**
 * One to many entity association
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
class OneToMany extends Base implements IAssociation
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
			$this->name = lcfirst(Tools::pluralize($targetEntityMetaData->name));
		else
			$this->name = $name;
		
		if (empty($sourceColumn))
			$this->sourceColumn = Tools::underscore($sourceEntityMetaData->primaryKey);
		elseif ($sourceEntityMetaData->hasColumn($sourceColumn))
			$this->sourceColumn = $sourceColumn;
		else
			throw new \InvalidArgumentException("Source column '".$sourceColumn."' is not valid column '".$sourceEntity."' entity.");
		
		
		if (empty($targetColumn))
			$this->targetColumn = Tools::underscore($sourceEntityMetaData->name.ucfirst($this->sourceColumn));
		else
			$this->targetColumn = $targetColumn;
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
	 * @return ActiveMapper\RepositoryCollection
	 */
	public function getData($assocKey)
	{	
		return new \ActiveMapper\RepositoryCollection($this->targetEntity, \dibi::select("*")
			->from(Manager::getEntityMetaData($this->targetEntity)->tableName)
			->where("[".$this->targetColumn."] = "
				.\ActiveMapper\Manager::getRepository($this->sourceEntity)->getModificator($this->sourceColumn), $assocKey));
	}
}