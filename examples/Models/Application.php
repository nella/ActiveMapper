<?php
namespace App\Models;

/**
 * @ManyToOne(App\Models\Author)
 * @ManyToMany(App\Models\Tag)
 */
class Application extends \ActiveMapper\ServiceEntity
{
	/**
	 * @column(Int)
	 * @autoincrement
	 * @primary
	 */
	private $id;
	/**
	 * @column(String, 50)
	 */
	private $title;
	/**
	 * @column(String, 100)
	 */
	private $web;
	/**
	 * @column(String, 100)
	 */
	private $slogan;
}