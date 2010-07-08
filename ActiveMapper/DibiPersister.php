<?php
/**
 * ActiveMapper
 *
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @license    http://nellacms.com/license  New BSD License
 * @link       http://addons.nette.org/cs/active-mapper
 * @category   ActiveMapper
 * @package    ActiveMapper
 */

namespace ActiveMapper;

use DibiConnection;

/**
 * Dibi persister
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 */
class DibiPersister extends \Nette\Object implements IPersister
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
	/** @var DibiConnection */
	private $connection;

	/**
	 * Constructor
	 *
	 * @param ActiveMapper\Manager $em
	 * @param string $entity
	 * @param DibiConnection $connection
	 * @throws InvalidArgumentException
	 */
	public function __construct(Manager $em, $entity, DibiConnection $connection = NULL)
	{
		// TODO: verify entity class

		$this->em = $em;
		$this->entity = $entity;
		$this->connection = $connection;
		if ($connection == NULL)
			$this->connection = $this->em->connection;
	}

	/**
	 * Get values
	 *
	 * @param mixed $entity
	 * @return array
	 */
	protected function getValues(&$entity)
	{
		return $this->em->getIdentityMap(get_class($entity))->getSavedValues($entity);
	}

	/**
	 * Insert data
	 *
	 * @param mixed $entity
	 */
	public function insert($entity)
	{
		$metadata = Metadata::getMetadata($this->entity);
		return $this->connection->insert($metadata->tableName, $this->getValues($entity))->execute();
	}

	/**
	 * Update data
	 *
	 * @param mixed $entity
	 */
	public function update($entity)
	{
		$metadata = Metadata::getMetadata($this->entity);
		return $this->connection->update($metadata->tableName, $this->getValues($entity))
				->where("[".$metadata->primaryKey."] = ".$this->getModificator($metadata->primaryKey),
						$metadata->getPrimaryKeyValue($entity))->execute();
	}

	/**
	 * Delete data
	 *
	 * @param mixed $entity
	 */
	public function delete($entity)
	{
		$metadata = Metadata::getMetadata($this->entity);
		return $this->connection->delete($metadata->tableName)
				->where("[".$metadata->primaryKey."] = ".$this->getModificator($metadata->primaryKey),
						$metadata->getPrimaryKeyValue($entity))->execute();
	}

	/**
	 * Get last generated primary key (autoincrement)
	 *
	 * @param string $sequence
	 * @return mixed
	 */
	public function lastPrimaryKey($sequence = NULL)
	{
		return $this->connection->getInsertId($sequence);
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

	/**
	 * Dibi persister factory
	 *
	 * @param ActiveMapper\Manager $em
	 * @param string $entity
	 * @param DibiConnection $connection
	 * @return ActiveMapper\DibiPersister
	 */
	public static function getDibiPersister(Manager $em, $entity, DibiConnection $connection = NULL)
	{
		return new static($em, $entity, $connection);
	}
}