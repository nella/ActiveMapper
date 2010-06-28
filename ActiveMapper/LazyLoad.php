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
	private $entityClass;
	/** @var string */
	private $column;
	/** @var mixed */
	private $primaryKey;
	/** @var mixed */
	private $data;

	/**
	 * Constructor
	 *
	 * @param string $entityClass
	 * @param string $column entity column name
	 * @param mixed $primaryKey
	 * @throws InvalidArgumentException
	 * @throws NotImplementedException
	 */
	public function __construct($entityClass, $column, $primaryKey)
	{
		if (!class_exists($entityClass) || !\Nette\Reflection\ClassReflection::from($entityClass)->implementsInterface('ActiveMapper\IEntity'))
			throw new \InvalidArgumentException("Argument \$entity must implements 'ActiveMapper\\IEntity'. [".$entityClass."]");
		if (!Manager::getEntityMetaData($entityClass)->hasColumn($column))
			throw new \InvalidArgumentException("Column '".$column."' must be valid '".$entityClass."' column");

		$this->entityClass = $entityClass;
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
		if (empty($this->data))
			$this->data = Manager::getRepository($this->entityClass)->lazyLoad($this->column, $this->primaryKey);

		if ($this->data === FALSE)
			throw new \InvalidStateException("Primary key '".$this->primaryKey."' for '".$this->entityClass."' not exist");

		return $this->data;
	}
}