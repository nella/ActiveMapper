<?php
namespace App\Models;

/**
 * @ManyToMany(App\Models\Application, mapped = FALSE)
 */
class Tag extends \ActiveMapper\Proxy
{
	/**
	 * @column(Int)
	 * @autoincrement
	 * @primary
	 */
	protected $id;
	/**
	 * @column(String, 20)
	 */
	protected $name;
}