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
	 * Get columns map
	 *
	 * @return array|null
	 */
	public static function getColumnsMap();
	
	/**
	 * Get associations map
	 *
	 * @return array|null
	 */
	public static function getAssociationsMap();
	
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
	 * Has column mapped
	 * 
	 * @return bool
	 */
	public static function hasMappedColumn($name);
	
	/**
	 * Get mapped column
	 * 
	 * @return ActiveMapper\IDataType
	 */
	public static function getMappedColumn($name);
}