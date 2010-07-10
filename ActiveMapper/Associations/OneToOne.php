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
 * @property-read bool $mapped
 * @property-read string $sourceColumn source column name
 * @property-read string $targetColumn target column name
 */
final class OneToOne extends Base implements IAssociation
{
	/** @var bool */
	protected $mapped;
	/** @var string */
	protected $name;
	/** @var string */
	protected $column;

	/**
	 * Costructor
	 *
	 * @param string $sourceEntity valid source entity class
	 * @param string $targetEntity valid target entity class
	 * @param bool $mapped is this association mapped/inversed
	 * @param string $name
	 * @param string $column target/source column name
	 * @throws InvalidArgumentException
	 */
	public function __construct($sourceEntity, $targetEntity, $mapped = TRUE, $name = NULL, $column = NULL)
	{
		parent::__construct($sourceEntity, $targetEntity);

		$this->mapped = $mapped;

		$metadata = Metadata::getMetadata($targetEntity);
		if (empty($name))
			$this->name = lcfirst($metadata->name);
		else
			$this->name = $name;

		if (empty($column)) {
			if ($this->mapped) {
				$metadata = Metadata::getMetadata($sourceEntity);
				$this->column = Tools::underscore($metadata->name.ucfirst($metadata->primaryKey));
			} else
				$this->column = Tools::underscore($metadata->name.ucfirst($metadata->primaryKey));
		} else
			$this->column = $column;
	}

	/**
	 * Get source column
	 *
	 * @return string
	 */
	public function getSourceColumn()
	{
		if ($this->mapped)
			return Metadata::getMetadata($this->sourceEntity)->primaryKey;
		else
			return $this->column;
	}

	/**
	 * Get target column
	 *
	 * @return string
	 */
	public function getTargetColumn()
	{
		if ($this->mapped)
			return $this->column;
		else
			return Metadata::getMetadata($this->targetEntity)->primaryKey;
	}

	/**
	 * Is association mapped
	 * - FALSE inverset
	 * 
	 * @return bool
	 */
	public function isMapped()
	{
		return $this->mapped;
	}

	/**
	 * Is association mapped
	 * - FALSE inverset
	 *
	 * @return bool
	 */
	public function getMapped()
	{
		return $this->isMapped();
	}
}