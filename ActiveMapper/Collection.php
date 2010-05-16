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

/**
 * Collection
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 */
class Collection extends \Nette\FreezableObject implements \ArrayAccess, \Countable, \IteratorAggregate
{
	/** @var array */
	protected $data;

	/**
	 * Count
	 *
	 * @return int
	 */
	public function count()
	{
		return count($this->data);
   	}

	/**
	 * Offset set
	 *
	 * @param int $offset
	 * @param ActiveMapper\IEntity $value
	 * @return ActiveMapper\IEntity
	 * @throws InvalidArgumentException
	 */
	public function offsetSet($offset, $value)
	{
		if (!($value instanceof IEntity))
			throw new \InvalidArgumentException("This collection accepted only ActiveMapper\\IEntity object");
		
		return $this->data[$offset] = $value;
   	}

	/**
	 * Offset exists
	 *
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return isset($this->data[$offset]);
   	}

	/**
	 * Offset unset
	 *
	 * @param mixed $offset
	 * @return ActiveMapper\Collection
	 */
	public function offsetUnset($offset)
	{
		unset($this->data[$offset]);
		return $this;
   	}

	/**
	 * Offset get
	 *
	 * @param int $offset
	 * @return ActiveMapper\IEntity|NULL
	 */
	public function offsetGet($offset)
	{
		return isset($this->data[$offset]) ? $this->data[$offset] : NULL;
   	}
   	
   	/**
   	 * Get iterator
   	 * 
   	 * @return ArrayIterator
   	 */
   	public function getIterator()
   	{
		return new \ArrayIterator($this->data);
   	}
}