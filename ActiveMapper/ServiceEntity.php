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

use Nette\Reflection\ClassReflection;

/**
 * Service entity object
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 */
abstract class ServiceEntity extends Entity
{
	/**
	 * Find entity witch id (primary key) is ...
	 *
	 * @param mixed $primaryKey
	 * @return ActiveMapper\IEntity
	 * @throws InvalidArgumentException
	 */
	public static function find($primaryKey)
	{
		return Manager::find(get_called_class(), $primaryKey);
	}

	/**
	 * Find all entity
	 *
	 * @return ActiveMapper\RepositoryCollection
	 * @throws InvalidArgumentException
	 */
	public static function findAll()
	{
		return Manager::findAll(get_called_class());
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
		if (strncmp($name, 'findBy', 6) === 0) {
			return callback('ActiveMapper\Manager', $name)->invokeArgs(array_merge(array(get_called_class()), $args));
		}
		else
			return parent::__callStatic($name, $args);
	}
}