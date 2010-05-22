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
	/**
	 * Find entity witch id (primary key) is ...
	 *
	 * @param string $entity
	 * @param mixed $primaryKey
	 * @return ActiveMapper\IEntity
	 * @throws InvalidArgumentException
	 */
	public static function find($entity, $primaryKey)
	{
		return Repository::find($entity, $primaryKey);
	}

	/**
	 * Find all entity
	 *
	 * @param string $entity
	 * @return ActiveMapper\Collection
	 * @throws InvalidArgumentException
	 */
	public static function findAll($entity)
	{
		return Repository::findAll($entity);
   	}

	/**
	 * Static method overload for findBy...
	 *
	 * @param string $name
	 * @param array $args
	 * @return ActiveMapper\Entity
	 * @throws InvalidArgumentException
	 */
	public static function __callStatic($name, $args)
	{
		if (strncmp($name, 'findBy', 6) === 0)
			return callback('ActiveMapper\Repository', $name)->invokeArgs($args);
		else
			return parent::__callStatic($name, $args);
	}
}