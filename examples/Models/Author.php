<?php
namespace App\Models;

/**
 * @OneToMany(App\Models\Application)
 * @OneToOne(App\Models\Blog, mapped="author")
 */
class Author extends \ActiveMapper\Proxy
{
	/**
	 * @column(Int)
	 * @autoincrement
	 * @primary
	 */
	protected $id;
	/**
	 * @column(String, 30)
	 * @unique
	 */
	protected $name;
	/**
	 * @column(String, 100)
	 */
	protected $web;
}