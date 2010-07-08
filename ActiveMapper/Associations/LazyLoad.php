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

use ActiveMapper\Metadata,
	ActiveMapper\Tools;

/**
 * Lazy load associations data
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper\Associations
 */
class LazyLoad extends \Nette\Object
{
	/** @var array */
	public static $modificators = array(
		'ActiveMapper\DataTypes\Bool' => "%b",
		'ActiveMapper\DataTypes\Date' => "%d",
		'ActiveMapper\DataTypes\DateTime' => "%t",
		'ActiveMapper\DataTypes\Float' => "%f",
		'ActiveMapper\DataTypes\Int' => "%i",
		'ActiveMapper\DataTypes\String' => "%sN",
		'ActiveMapper\DataTypes\Text' => "%sN",
	);
	/** @var ActiveMapper\Manager */
	private $em;
	/** @var string */
	private $entity;
	/** @var string */
	private $name;
	/** @var mixed */
	private $associationKey;

	/**
	 * Costructor
	 *
	 * @param ActiveMapper\Manager $em entity manager
	 * @param string $entity entity class name
	 * @param string $name association name
	 * @param array $entityData
	 */
	public function __construct(\ActiveMapper\Manager $em, $entity, $name, $entityData)
	{
		// TODO: verify entity class

		$this->em = $em;
		$this->entity = $entity;
		$this->name = $name;
		$metadata = Metadata::getMetadata($entity);
		if (!isset($metadata->associations[$name]))
			throw new \InvalidArgumentException("Entity '$entity' not have association '$name'");
		$this->associationKey = $entityData[$metadata->associations[$name]->sourceColumn];
	}

	/**
	 * Get data
	 *
	 * @return mixed
	 */
	public function getData()
	{
		$metadata = Metadata::getMetadata($this->entity);
		$assoc = $metadata->associations[$this->name];
		if ($assoc instanceof OneToOne && $assoc->mapped) {
			return $this->em->getIdentityMap($assoc->targetEntity)->map(
				$this->em->connection->select("*")->from(Metadata::getMetadata($assoc->targetEntity)->tableName)
				->where("[{$assoc->targetColumn}] = ".$this->getModificator($assoc->sourceColumn), $this->associationKey)
				->execute()->fetch()
			);
		} elseif ($assoc instanceof OneToOne && !$assoc->mapped) {
			return $this->em->find($assoc->targetEntity, $this->associationKey);
		} elseif ($assoc instanceof ManyToMany) {
			$targetTable = Metadata::getMetadata($assoc->targetEntity)->tableName;
			return $this->em->getIdentityMap($assoc->targetEntity)->map(
				$this->em->connection->select("[$targetTable].*")->from($targetTable)
				->innerJoin($assoc->joinTable)->on("[{$assoc->joinTable}].[{$assoc->joinSourceColumn}] = "
						.$this->getModificator($assoc->sourceColumn), $this->associationKey)
				->and("[{$assoc->joinTable}].[{$assoc->joinTargetColumn}] = ["
						.Metadata::getMetadata($assoc->targetEntity)->tableName."].[{$assoc->targetColumn}]")
				->execute()->fetchAll()
			);
		} elseif ($assoc instanceof OneToMany) {
			return $this->em->getIdentityMap($assoc->targetEntity)->map(
				$this->em->connection->select("*")->from(Metadata::getMetadata($assoc->targetEntity)->tableName)
				->where("[{$assoc->targetColumn}] = ".$this->getModificator($assoc->sourceColumn), $this->associationKey)
				->execute()->fetchAll()
			);
		} else {
			return $this->em->find($assoc->targetEntity, $this->associationKey);
		}
	}

	/**
	 * Get modificator
	 *
	 * @param string $column entity column name
	 * @return string
	 * @throws InvalidArgumentException
	 */
	protected function getModificator($column)
	{
		$metadata = Metadata::getMetadata($this->entity);
		if (!$metadata->hasColumn($column))
			throw new \InvalidArgumentException("Entity '{$this->entity}' has not '$column' column");

		$class = $metadata->getColumn($column)->reflection->name;
		if (!in_array($class, array_keys(self::$modificators)))
			throw new \NotImplementedException("Support for '$class' datatype not implemented in '".get_called_class()."'");

		return self::$modificators[$class];
	}
}