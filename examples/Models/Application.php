<?php
namespace App\Models;

/**
 * @ManyToOne(App\Models\Author)
 * @ManyToMany(App\Models\Tag)
 */
class Application extends \ActiveMapper\Proxy
{
	/**
	 * @column(Int)
	 * @autoincrement
	 * @primary
	 */
	protected $id;
	/**
	 * @column(String, 50)
	 */
	protected $title;
	/**
	 * @column(String, 100)
	 */
	protected $web;
	/**
	 * @column(String, 100)
	 */
	protected $slogan;
}