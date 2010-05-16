<?php
namespace App\Models;

/**
 * @OneToMany(App\Models\Article)
 * @OneToOne(App\Models\Profile)
 */
class Author extends \ActiveMapper\ServiceEntity
{
	/**
	 * @column(Int)
	 * @autoincrement
	 * @primary
	 */
	protected $id;
	/**
	 * @column(String, 100)
	 * @unique
	 */
	protected $name;
}