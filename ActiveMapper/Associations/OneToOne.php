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

use dibi,
	ActiveMapper\Tools,
	ActiveMapper\Metadata;

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
 * @property-read bool $mapped
 */
class OneToOne extends Base implements IAssociation
{
	/** @var bool */
	protected $mapped;
	/** @var string */
	protected $name;
	/** @var string */
	protected $targetColumn;
	/** @var string */
	protected $sourceColumn;
	
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
		$targetMetadata = Metadata::getMetadata($targetEntity);
		$sourceMetadata = Metadata::getMetadata($sourceEntity);
		
		$this->mapped = $mapped;
		
		if (empty($name))
			$this->name = lcfirst($targetMetadata->name);
		else
			$this->name = $name;
		
		if ($this->mapped) {
			if (empty($sourceColumn))
				$this->sourceColumn = Tools::underscore($sourceMetadata->primaryKey);
			else
				$this->sourceColumn = $sourceColumn;
			
			if (empty($targetColumn))
				$this->targetColumn = Tools::underscore($sourceMetadata->name.ucfirst($this->sourceColumn));
			else
				$this->targetColumn = $targetColumn;
		} else {
			if (empty($targetColumn))
				$this->targetColumn = Tools::underscore($targetMetadata->primaryKey);
			else
				$this->targetColumn = $targetColumn;
			
			if (empty($sourceColumn))
				$this->sourceColumn = Tools::underscore($targetMetadata->name.ucfirst($this->targetColumn));
			else
				$this->sourceColumn = $sourceColumn;
		}
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
	 * Is association mapped
	 * - FALSE inverset
	 *
	 * @return bool
	 */
	final public function getMapped()
	{
		return $this->isMapped();
	}
}