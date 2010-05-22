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
	 * Get entity name
	 *
	 * @return string
	 */
	public static function getEntityName();
	
	/**
	 * Get table name
	 *
	 * @return string
	 */
	public static function getTableName();

	/**
	 * Has primary key
	 *
	 * @return bool
	 */
	public static function hasPrimaryKey();

	/**
	 * Get primary key name
	 *
	 * @return string|null
	 */
	public static function getPrimaryKey();
	
	/**
	 * Get columns meta data
	 *
	 * @return array|null
	 */
	public static function getColumnsMetaData();

	/**
	 * Has column mapped
	 *
	 * @return bool
	 */
	public static function hasColumnMetaData($name);

	/**
	 * Get mapped column
	 *
	 * @return ActiveMapper\IDataType
	 */
	public static function getColumnMetaData($name);

	/**
	 * Get associations meta data
	 *
	 * @return array|null
	 */
	public static function getAssociationsMetaData();
}