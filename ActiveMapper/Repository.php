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

use dibi;
use Nette\Reflection\ClassReflection;
use ActiveMapper\Tools;

/**
 * Repository
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 */
abstract class Repository extends \Nette\Object
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
		if (!class_exists($entity) || !ClassReflection::from($entity)->implementsInterface('ActiveMapper\IEntity'))
			throw new \InvalidArgumentException("Entity [".$entity."] must implements 'ActiveMapper\\IEntity'");
		if (!$entity::hasPrimaryKey())
			throw new \InvalidArgumentException("Entity [".$entity."] has not set PRIMARY KEY");

		return \dibi::select("*")->from($entity::getTableName())
				->where("[".Tools::underscore($entity::getPrimaryKey())."] = "
					.self::getModificator($entity, $entity::getPrimaryKey()), $primaryKey)
				->execute()->setRowClass($entity)->fetch();
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
		if (!class_exists($entity) || !ClassReflection::from($entity)->implementsInterface('ActiveMapper\IEntity'))
			throw new \InvalidArgumentException("Entity [".$entity."] must implements 'ActiveMapper\\IEntity'");
		
		return new RepositoryCollection($entity);
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
			if (!ClassReflection::from($args[0])->implementsInterface('ActiveMapper\IEntity'))
				throw new \InvalidArgumentException("Entity [".$args[0]."] must implements 'ActiveMapper\\IEntity'");

			$name = lcfirst(substr($name, 6));
           	return \dibi::select("*")->from($args[0]::getTableName())
				->where("[".Tools::underscore($name)."] = ".self::getModificator($args[0], $name), $args[1])
				->execute()->setRowClass($args[0])->fetch();
		}
		else
			return parent::__callStatic($name, $args);
	}
	
	/**
	 * Get modificator
	 * 
	 * @param string $entity
	 * @param string $column entity column name
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public static function getModificator($entity, $column)
	{
		if (!class_exists($entity) || !\Nette\Reflection\ClassReflection::from($entity)->implementsInterface('ActiveMapper\IEntity'))
			throw new \InvalidArgumentException("Entity [".$entity."] must implements 'ActiveMapper\\IEntity'");
		if (!$entity::hasColumnMetaData($column))
			throw new \InvalidArgumentException("Entity [".$entity."] has not '".$column."' column");
		
		switch($entity::getColumnMetaData($column)->reflection->name)
		{
			case 'ActiveMapper\DataTypes\Bool':
				return '%b';
				break;
			case 'ActiveMapper\DataTypes\Date':
				return '%d';
				break;
			case 'ActiveMapper\DataTypes\DateTime':
			case 'ActiveMapper\DataTypes\Time':
				return '%t';
				break;
			case 'ActiveMapper\DataTypes\Float':
				return '%f';
				break;
			case 'ActiveMapper\DataTypes\Int':
				return '%i';
				break;
			default:
				return '%sN';
				break;
		}
	}
}