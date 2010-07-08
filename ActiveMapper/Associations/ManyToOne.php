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
	 * @param string $name
	 * @param string $targetColumn valid targer column name
	 * @param string $sourceColumn valid source column name
	 * @throws InvalidArgumentException
	 */
	public function __construct($sourceEntity, $targetEntity, $name = NULL, $targetColumn = NULL, $sourceColumn = NULL)
	{
		parent::__construct($sourceEntity, $targetEntity);
		$targetMetadata = Metadata::getMetadata($targetEntity);
		$sourceMetadata = Metadata::getMetadata($sourceEntity);

		if (empty($name))
			$this->name = lcfirst($targetMetadata->name);
		else
			$this->name = $name;

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