<?php
namespace App\Models;

/**
 * @OneToOne(App\Models\Author, mapped = FALSE)
 */
class Profile extends \ActiveMapper\ServiceEntity
{
	/**
	 * @column(String, 255)
	 */
	private $web;
}