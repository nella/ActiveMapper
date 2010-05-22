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
 * Entity interface
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 */
interface IEntity
{
	/**
	 * Getter
	 *
	 * @param string $name
	 * @return mixed
	 * @throws MemberAccessException
	 */
	public function &__get($name);

	/**
	 * Setter
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return mixed
	 * @throws MemberAccessException
	 */
	public function __set($name, $value);

	/**
	 * Method overload for associations
	 *
	 * @param string $name associtation name
	 * @param array $args
	 * @return mixed
	 * @throws MemberAccessException
	 */
	public function __call($name, $args);
}