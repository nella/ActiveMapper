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

/**
 * Unit of work
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 */
class UnitOfWork extends \Nette\Object
{
	/** @var ActiveMapper\Manager */
	private $em;
	/** @var array */
	private $insertEntities;
	/** @var array */
	private $updateEntities;
	/** @var array */
	private $deleteEntities;
	/** @var int */
	private $count;

	/**
	 * Constructor
	 *
	 * @param Manager $em
	 */
	public function __construct(Manager $em)
	{
		$this->em = $em;
		$this->count = 0;
		$this->insertEntities = $this->updateEntities = $this->deleteEntities = array();
	}

	/**
	 * Register entity for save
	 *
	 * @param mixed $entity
	 * @return ActiveMapper\UnitOfWork
	 */
	public function registerSave(&$entity)
	{
		if ($this->em->getIdentityMap(get_class($entity))->isMapped($entity))
			$this->updateEntities[] = &$entity;
		else
			$this->insertEntities[] = &$entity;

		$this->count++;

		return $this;
	}

	/**
	 * Register entity for delete
	 *
	 * @param mixed $entity
	 * @return ActiveMapper\UnitOfWork
	 */
	public function registerDelete(&$entity)
	{
		$this->deleteEntities[] = &$entity;
		$this->count++;

		return $this;
	}

	/**
	 * Commit
	 *
	 * @throws InvalidStateException
	 */
	public function commit()
	{
		try {
			$this->em->connection->begin();
			if (count($this->deleteEntities) >= 1) {
				foreach ($this->deleteEntities as $entity) {
					$this->em->getPersister(get_class($entity))->delete($entity);
					$this->em->getIdentityMap(get_class($entity))->detach($entity);
				}
			}
			if (count($this->updateEntities) >= 1) {
				foreach ($this->updateEntities as $entity) {
					$this->em->getPersister(get_class($entity))->update($entity);
					$this->em->getIdentityMap(get_class($entity))->remap($entity);
				}
			}
			if (count($this->insertEntities) >= 1) {
				foreach ($this->insertEntities as $entity) {
					$persister = $this->em->getPersister(get_class($entity));
					$persister->insert($entity);
					$metadata = Metadata::getMetadata(get_class($entity));
					if ($metadata->primaryKeyAutoincrement)
						$metadata->setPrimaryKeyValue($entity, $persister->lastPrimaryKey());
					$this->em->getIdentityMap(get_class($entity))->store($entity);
				}
			}
			$this->em->connection->commit();
			$this->insertEntities = $this->updateEntities = $this->deleteEntities = array();
			$this->count = 0;
		} catch (\Exception $e) {
			$this->em->connection->rollback();
			throw new \InvalidStateException("When saving changes has error occurred.", NULL, $e);
		}
	}

	/**
	 * Get count
	 *
	 * @return int
	 */
	public function getCount()
	{
		return $this->count();
	}

	/**
	 * Get count
	 *
	 * @return int
	 */
	public function count()
	{
		return $this->count;
	}

	/**
	 * Unit of work factory
	 *
	 * @param Manage $em
	 * @return ActiveMapper\UnitOfWork
	 */
	public static function getUnitOfWork(Manager $em)
	{
		return new static($em);
	}
}