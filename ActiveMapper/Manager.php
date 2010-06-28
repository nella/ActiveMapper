<?php
/**
 * ActiveMapper
 *
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @license    http://nellacms.com/license  New BSD License
 * @link       http://addons.nettephp.com/cs/active-mapper
 * @category   ActiveMapper
 * @package    ActiveMapper
 */

namespace ActiveMapper;

/**
 * Entity manager
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 */
abstract class Manager extends \Nette\Object
{
	/** @var array */
	private static $metaData = array();
	/** @var array */
	private static $repositories = array();

	/**
	 * Get entity metadata
	 * 
	 * @param string $entity valid entity class
	 * @return ActiveMapper\Metadata
	 */
	public static function getEntityMetaData($entity)
	{
		if (!isset(self::$metaData[$entity]))
		{
			$cache = ORM::getCache('metaData');
			if (isset($cache[$entity]) && !ORM::$metaDataCache)
				self::$metaData[$entity] = $cache[$entity];
			else
			{
				self::$metaData[$entity] = new Metadata($entity);

				if (!ORM::$metaDataCache)
				{
					$cache->save($entity, self::$metaData[$entity]->toCache(),array(
						'files' => array(\Nette\Reflection\ClassReflection::from($entity)->getFileName()),
					));
				}
			}

		}

		return self::$metaData[$entity];
	}
	
	/**
	 * Get repository
	 * 
	 * @param string $entityClass
	 * @return IRepository
	 */
	public static function getRepository($entityClass)
	{
		if (!isset(self::$repositories[$entityClass]))
			self::$repositories[$entityClass] = new Repository($entityClass);
			
		return self::$repositories[$entityClass];
	}
	
	/**
	 * Set repository
	 * 
	 * @param string $entityClass
	 * @param IRepository $repository
	 */
	public static function setRepository($entityClass, IRepository $repository)
	{
		self::$repositories[$entityClass] = $repository;
	}

	/**
	 * Find entity witch id (primary key) is ...
	 *
	 * @param string $entityCLass
	 * @param mixed $primaryKey
	 * @return ActiveMapper\IEntity
	 * @throws InvalidArgumentException
	 */
	public static function find($entityClass, $primaryKey)
	{
		return self::getRepository($entityClass)->find($primaryKey);
	}

	/**
	 * Find all entity
	 *
	 * @param string $entityClass
	 * @return ActiveMapper\RepositoryCollection
	 * @throws InvalidArgumentException
	 */
	public static function findAll($entityClass)
	{
		return self::getRepository($entityClass)->findAll();
   	}

	/**
	 * Static method overload for findBy...
	 *
	 * @param string $name
	 * @param array $args
	 * @return ActiveMapper\IEntity
	 * @throws InvalidArgumentException
	 */
	public static function __callStatic($name, $args)
	{
		if (strncmp($name, 'findBy', 6) === 0)
		{
			$entityClass = $args[0];
			unset($args[0]);
			return callback(self::getRepository($entityClass), $name)->invokeArgs($args);
		}
		else
			return parent::__callStatic($name, $args);
	}
}