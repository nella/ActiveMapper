<?php
namespace App\Models;

/**
 * @ManyToMany(App\Models\Article, mapped = FALSE)
 */
class Tag extends \ActiveMapper\ServiceEntity
{
	/**
	 * @column(Int)
	 * @autoincrement
	 * @primary
	 */
	private $id;
	/**
	 * @column(String, 255)
	 */
	private $name;
}