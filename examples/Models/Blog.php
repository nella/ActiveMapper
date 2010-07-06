<?php
namespace App\Models;

/**
 * @OneToOne(App\Models\Author, mapped = FALSE)
 */
class Blog
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
	private $name;
	/**
	 * @column(String, 100)
	 */
	private $url;
}