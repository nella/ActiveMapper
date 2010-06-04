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

use ActiveMapper\Manager,
	Nette\Reflection\ClassReflection;

/**
 * Base entity association
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper\Associations
 * @property-read string $sourceEntity source entity class
 * @property-read string $targetEntity target entity class
 * @property-read string $sourceTable source table name
 * @property-read string $targetTable target table name
 */
abstract class Base extends \Nette\Object
{
	/** @var string */
	private $sourceEntity;
	/** @var string */
	private $targetEntity;

	/**
	 * Costructor
	 *
	 * @param string $sourceEntity valid source entity class
	 * @param string $targetEntity valid target entity class
	 * @throws InvalidArgumentException
	 */
	public function __construct($sourceEntity, $targetEntity)
	{
		if (!class_exists($sourceEntity) || !ClassReflection::from($sourceEntity)->implementsInterface('ActiveMapper\IEntity'))
			throw new \InvalidArgumentException("Argument \$sourceEntity must implements 'ActiveMapper\\IEntity'. [".$sourceEntity."]");
		if (!class_exists($targetEntity) || !ClassReflection::from($sourceEntity)->implementsInterface('ActiveMapper\IEntity'))
			throw new \InvalidArgumentException("Argument \$targetEntity must implements 'ActiveMapper\\IEntity'. [".$targetEntity."]");
		
		$this->sourceEntity = $sourceEntity;
		$this->targetEntity = $targetEntity;
	}

	/**
	 * Get source entity class
	 *
	 * @return string
	 */
	final public function getSourceEntity()
	{
		return $this->sourceEntity;
	}

	/**
	 * Get target entity class
	 *
	 * @return string
	 */
	final public function getTargetEntity()
	{
		return $this->targetEntity;
	}
	
	/**
	 * Get source table name
	 * 
	 * @return string
	 */
	final public function getSourceTable()
	{
		return Manager::getEntityMetaData($this->sourceEntity)->tableName;
	}
	
	/**
	 * Get target table name
	 * 
	 * @return string
	 */
	final public function getTargetTable()
	{
		return Manager::getEntityMetaData($this->targetEntity)->tableName;
	}
}