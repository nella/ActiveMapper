<?php
namespace App\Models;

/**
 * @OneToOne(App\Models\Author, mapped = FALSE)
 */
class Blog extends \ActiveMapper\Proxy
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
	protected $name;
	/**
	 * @column(String, 100)
	 */
	protected $url;
}