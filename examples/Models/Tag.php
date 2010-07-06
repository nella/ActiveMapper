<?php
namespace App\Models;

/**
 * @ManyToMany(App\Models\Application, mapped = FALSE)
 */
class Tag
{
	/**
	 * @column(Int)
	 * @autoincrement
	 * @primary
	 */
	private $id;
	/**
	 * @column(String, 20)
	 */
	private $name;
}