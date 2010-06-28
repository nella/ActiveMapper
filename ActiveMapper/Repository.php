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

use dibi,
	Nette\Reflection\ClassReflection,
	ActiveMapper\Tools;

/**
 * Repository
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 * @property-read string $entityClass
 */
class Repository extends \Nette\Object implements IRepository
{
	/** @var string */
	private $entityClass;
	
	/**
	 * Constructor
	 * 
	 * @param string $entityName
	 * @throws InvalidArgumentException
	 */
	public function __construct($entityClass)
	{
		if (!class_exists($entityClass) || !ClassReflection::from($entityClass)->implementsInterface('ActiveMapper\IEntity'))
			throw new \InvalidArgumentException("Entity [".$entityClass."] must implements 'ActiveMapper\\IEntity'");
		
		$this->entityClass = $entityClass;
	}
	
	/**
	 * Get entity class
	 */
	public function getEntityClass()
	{
		return $this->entityClass;
	}
	
	/**
	 * Find entity witch id (primary key) is ...
	 *
	 * @param mixed $primaryKey
	 * @return ActiveMapper\IEntity
	 * @throws InvalidArgumentException
	 */
	public function find($primaryKey)
	{
		return dibi::select("*")->from(static::getMetaData()->tableName)
				->where("[".Tools::underscore(static::getMetaData()->primaryKey)."] = "
					.$this->getModificator(static::getMetaData()->primaryKey), $primaryKey)
				->execute()->setRowClass($this->entityClass)->fetch();
	}

	/**
	 * Find all entity
	 *
	 * @return ActiveMapper\RepositoryCollection
	 * @throws InvalidArgumentException
	 */
	public function findAll()
	{
		return new RepositoryCollection($this->entityClass);
   	}

	/**
	 * Method overload for findBy...
	 *
	 * @param string $name
	 * @param array $args
	 * @return ActiveMapper\IEntity
	 * @throws InvalidArgumentException
	 */
	public function __call($name, $args)
	{
		if (strncmp($name, 'findBy', 6) === 0) {
			$name = lcfirst(substr($name, 6));
           	return \dibi::select("*")->from($this->getMetaData()->tableName)
				->where("[".Tools::underscore($name)."] = ".$this->getModificator($name), $args[0])
				->execute()->setRowClass($this->entityClass)->fetch();
		} else
			return parent::__callStatic($name, $args);
	}
	
	/**
	 * Get entity metadata
	 * 
	 * @return ActiveMapper\EntityMetaData
	 */
	private function getMetaData()
	{
		return Manager::getEntityMetaData($this->entityClass);
	}
	
	/**
	 * Get modificator
	 * 
	 * @param string $column entity column name
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function getModificator($column)
	{
		if (!$this->getMetaData()->hasColumn($column))
			throw new \InvalidArgumentException("Entity [".$this->entityClass."] has not '".$column."' column");
		
		switch($this->getMetaData()->getColumn($column)->reflection->name) {
			case 'ActiveMapper\DataTypes\Bool':
				return '%b';
				break;
			case 'ActiveMapper\DataTypes\Date':
				return '%d';
				break;
			case 'ActiveMapper\DataTypes\DateTime':
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

	/**
	 * Lazy load column
	 *
	 * @param string $column valid entity column name
	 * @param mixed $primaryKey
	 * @return mixed
	 * @throws InvalidArgumentException
	 */
	public function lazyLoad($column, $primaryKey)
	{
		if (!$this->getMetaData()->hasColumn($column))
			throw new \InvalidArgumentException("Column '".$column."' must be valid '".$this->entityClass."' column");

		return \dibi::select($column)
			->from($this->getMetaData()->tableName)
			->where("[".$this->getMetaData()->primaryKey."] = ".$this->getModificator($this->getMetaData()->primaryKey), $primaryKey)
			->fetchSingle();
	}
	
	/**
	 * Repository factory
	 * 
	 * @return ActiveMapper\Repository
	 */
	public static function factory($entityClass)
	{
		$class = get_called_class();
		return new $class($entityClass);
	}
}