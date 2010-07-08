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
	ActiveMapper\Metadata;

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
 * @property-read string $joinSourceColumn join table source column name
 * @property-read string $joinTargetColumn join table target column name
 * @property-read bool $mapped
 */
class ManyToMany extends Base implements IAssociation
{
	/** @var bool */
	protected $mapped;
	/** @var string */
	protected $name;
	/** @var string */
	protected $targetColumn;
	/** @var string */
	protected $sourceColumn;
	/** @var string */
	protected $joinTable;
	/** @var string */
	protected $joinTargetColumn;
	/** @var string */
	protected $joinSourceColumn;

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
	 * @param string $joinTargetColumn valid join table target column name
	 * @param string $joinSourceColumn valid join table source column name
	 * @throws InvalidArgumentException
	 */
	public function __construct($sourceEntity, $targetEntity, $mapped = TRUE, $name = NULL, $sourceColumn = NULL, $targetColumn = NULL, $joinTable = NULL, $joinTargetColumn = NULL, $joinSourceColumn = NULL)
	{
		parent::__construct($sourceEntity, $targetEntity);
		$targetMetadata = Metadata::getMetadata($targetEntity);
		$sourceMetadata = Metadata::getMetadata($sourceEntity);

		$this->mapped = $mapped;

		if (empty($name))
			$this->name = lcfirst(Tools::pluralize($targetMetadata->name));
		else
			$this->name = $name;


		if (empty($sourceColumn))
			$this->sourceColumn = Tools::underscore($sourceMetadata->primaryKey);
		else
			$this->sourceColumn = $sourceColumn;

		if (empty($targetColumn))
			$this->targetColumn = Tools::underscore($targetMetadata->primaryKey);
		else
			$this->targetColumn = $targetColumn;

		if (empty($joinSourceColumn))
			$this->joinSourceColumn = Tools::underscore($sourceMetadata->name.ucfirst($this->sourceColumn));
		else
			$this->joinSourceColumn = $joinSourceColumn;

		if (empty($joinTargetColumn))
			$this->joinTargetColumn = Tools::underscore($targetMetadata->name.ucfirst($this->targetColumn));
		else
			$this->joinTargetColumn = $joinTargetColumn;


		if (!empty($joinTable))
			$this->joinTable = $joinTable;
		elseif ($this->mapped) {
			$this->joinTable = Tools::underscore(
				Tools::pluralize($sourceMetadata->name).ucfirst(Tools::pluralize($targetMetadata->name))
			);
		} else {
			$this->joinTable = Tools::underscore(
				Tools::pluralize($targetMetadata->name).ucfirst(Tools::pluralize($sourceMetadata->name))
			);
		}
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
	final public function getJoinSourceColumn()
	{
		return $this->joinSourceColumn;
	}

	/**
	 * Get join table target column
	 * 
	 * @return string
	 */
	final public function getJoinTargetColumn()
	{
		return $this->joinTargetColumn;
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