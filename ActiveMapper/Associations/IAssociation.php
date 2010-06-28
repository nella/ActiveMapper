<?php
/**
 * ActiveMapper
 *
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @license    http://nellacms.com/license  New BSD License
 * @link       http://addons.nette.org/cs/active-mapper
 * @category   ActiveMapper
 * @package    ActiveMapper\Associations
 */

namespace ActiveMapper\Associations;

/**
 * Association interface
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper\Associations
 */
interface IAssociation
{
	/**
	 * Get source entity class
	 *
	 * @return string
	 */
	public function getSourceEntity();

	/**
	 * Get target entity class
	 *
	 * @return string
	 */
	public function getTargetEntity();
	
	/**
	 * Get source table name
	 *
	 * @return string
	 */
	public function getSourceTable();

	/**
	 * Get target table name
	 *
	 * @return string
	 */
	public function getTargetTable();
	
	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Get source column name
	 *
	 * @return string
	 */
	public function getSourceColumn();

	/**
	 * Get target column name
	 *
	 * @return string
	 */
	public function getTargetColumn();
	
	/**
	 * Get association data
	 * 
	 * @param mixed $assocKey
	 * @return mixed
	 */
	public function getData($assocKey);
}