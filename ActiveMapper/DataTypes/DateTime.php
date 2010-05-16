<?php
/**
 * ActiveMapper
 *
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @license    http://nellacms.com/license  New BSD License
 * @link       http://addons.nettephp.com/cs/active-mapper
 * @category   ActiveMapper
 * @package    ActiveMapper\DataTypes
 */

namespace ActiveMapper\DataTypes;

/**
 * Date time column class
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper\DataTypes
 *
 * @property-read string $name
 * @property-read bool $allowNull
 */
class DateTime extends Base implements IDataType
{
	/**
	 * Validate value
	 *
	 * @param string|DateTime $value
	 * @return bool
	 */
	public function validate($value)
	{
		if ($value === NULL && !$this->allowNull)
			return FALSE;
		elseif ($value === NULL)
			return TRUE;

		if ($value instanceof \DateTime || (bool)preg_match(
				'/^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9]) ([0-2][0-9]):([0-5][0-9]):([0-5][0-9])$/', $value))
			return TRUE;
		else
			return FALSE;
	}

	/**
	 * Sanitize value
	 *
	 * @param string|DateTime $value
	 * @return DateTime
	 * @throws InvalidArgumentException
	 */
	public function sanitize($value)
	{
		if ($value === NULL && !$this->allowNull)
			throw new \InvalidArgumentException("Null is not allowed value for ".$this->name);
		elseif ($value !== NULL && !$this->validate($value))
			throw new \InvalidArgumentException("Only date value / DateTime object accepted for '".$this->name."' [".$value."]");

		if ($value instanceof \DateTime)
			return $value;
		elseif ($value === NULL)
			return NULL;
		else
			return new \DateTime($value);
	}
}