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
		if (!static::hasPrimaryKey())
			throw new \InvalidArgumentException("Entity [".get_called_class()."] has not set PRIMARY KEY");

		return \dibi::select("*")->from(static::getTableName())->where("[".Tools::underscore(static::getPrimaryKey())."] = "
					.Repository::getModificator(get_called_class(), static::getPrimaryKey()), $primaryKey)->execute()
				->setRowClass(get_called_class())->fetch();
	}

	/**
	 * Find all entity
	 *
	 * @return ActiveMapper\Collection
	 * @throws InvalidArgumentException
	 */
	public static function findAll()
	{
		return new RepositoryCollection(get_called_class());
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
		{
			$name = lcfirst(substr($name, 6));
           	return \dibi::select("*")->from(static::getTableName())
				->where("[".Tools::underscore($name)."] = ".Repository::getModificator(get_called_class(), $name), $args[0])->execute()
				->setRowClass(get_called_class())->fetch();
		}
		else
			return parent::__callStatic($name, $args);
	}
}