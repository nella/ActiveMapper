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
 * Column lazy loader
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 * @property-read mixed $data get lazy loaded data
 */
class LazyLoad extends \Nette\Object
{
	/** @var string */
	private $entity;
	/** @var string */
	private $column;
	/** @var mixed */
	private $primaryKey;
	/** @var mixed */
	private $data;

	/**
	 * Constructor
	 *
	 * @param string $entity entity class
	 * @param string $column entity column name
	 * @param mixed $primaryKey
	 * @throws InvalidArgumentException
	 * @throws NotImplementedException
	 */
	public function __construct($entity, $column, $primaryKey)
	{
		if (!class_exists($entity) || !\Nette\Reflection\ClassReflection::from($entity)->implementsInterface('ActiveMapper\IEntity'))
			throw new \InvalidArgumentException("Argument \$entity must implements 'ActiveMapper\\IEntity'. [".$entity."]");
		if (!Manager::getEntityMetaData($entity)->hasPrimaryKey())
			throw new \NotImplementedException("Lazy load for entity without primary key not supported");
		if (!Manager::getEntityMetaData($entity)->hasColumn($column))
			throw new \InvalidArgumentException("Column '".$column."' must be valid '".$entity."' column");

		$this->entity = $entity;
		$this->column = $column;
		$this->primaryKey = $primaryKey;
	}

	/**
	 * Get data
	 *
	 * @return mixed
	 * @throws InvalidStateException
	 */
	public function getData()
	{
		$entity = $this->entity;
		if (empty($this->data))
		{
			$this->data = \dibi::select($this->column)
				->from(Manager::getEntityMetaData($entity)->tableName)
				->where("[".Manager::getEntityMetaData($entity)->primaryKey."] = %i", $this->primaryKey)
				->fetchSingle();
		}
		if ($this->data === FALSE)
			throw new \InvalidStateException("Primary key '".$this->primaryKey."' for '".$entity."' not exist");

		return $this->data;
	}
}