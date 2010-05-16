<?php
namespace App\Models;

/**
 * @ManyToOne(App\Models\Author)
 * @ManyToMany(App\Models\Tag)
 */
class Article extends \ActiveMapper\ServiceEntity
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
	private $title;
	/**
	 * @column(Text)
	 */
	private $content;
	/**
	 * @column(Date)
	 * @default NOW()
	 */
	private $create;
	/**
	 * @column(Float)
	 * @null
	 */
	private $price;
	/**
	 * @column(Bool)
	 * @default false
	 */
	private $public;
}