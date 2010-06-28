<?php
/**
 * ActiveMapper
 *
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @license    http://nellacms.com/license  New BSD License
 * @link       http://addons.nettephp.com/cs/active-mapper
 * @category   ActiveMapper
 * @package    ActiveMapper
 */

namespace ActiveMapper;

use Nette\Reflection\ClassReflection;

/**
 * Entity metada object
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 * @property-read string $tableName entity table name
 * @property-read array $columns entity columns array
 * @property-read array $associations entity associations array
 * @property-read array $associationsKeys entity associations keys array
 * @property-read string $primaryKey entity primary key name
 * @property-read string $name entity name
 */
class Metadata extends \Nette\Object
{
	/** @var string */
	private $tableName;
	/** @var array */
	private $columns;
	/** @var array */
	private $associations;
	/** @var array */
	private $associationsKeys;
	/** @var string */
	private $primaryKey;
	/** @var bool */
	private $primaryKeyAutoincrement;
	/** @var string */
	private $entity;
	/** @var string */
	private $name;
	/** @var bool */
	private $isAssociationsLoaded;

	public function __construct($entity)
	{

		$ref = new ClassReflection($entity);
		if (!class_exists($entity) || !$ref->implementsInterface('ActiveMapper\IEntity'))
			throw new \InvalidArgumentException("Argument \$entity must implements 'ActiveMapper\\IEntity'. [".$entity."]");

		$this->isAssociationsLoaded = FALSE;
		$this->entity = $entity;
		$this->associations = $this->columns = $this->associationsKeys = array();
		$this->tableName = $this->primaryKey = NULL;

		/********************************************************** Columns ***********************************************************p*v*/
		foreach ($ref->getProperties() as $property) {
			if ($property->hasAnnotation('column')) {
				$annotation = (array)$property->getAnnotation('column');
				$datatype = 'ActiveMapper\DataTypes\\'.$annotation[0];
				unset($annotation[0]);
				$params = array_merge(array($property->name, $property->hasAnnotation('null')), $annotation);

				if (!\class_exists($datatype))
					throw new \ActiveMapper\InvalidDataTypeException("Data type '" . $datatype . "' not exist");

				$this->columns[$property->name] = call_user_func_array(array(ClassReflection::from($datatype), 'newInstance'), $params);
			}

			if ($property->hasAnnotation('primary')) {
				if ($property->hasAnnotation('column')) {
					if (empty($this->primaryKey))
						$this->primaryKey = $property->name;
					else
						throw new \NotImplementedException("Multiple column primary key not implemented [".$entity."]");
				} else
					throw new \NotImplementedException("Primary key must be column [".$entity."::".$property->name."]");
			}

			if ($property->hasAnnotation('autoincrement'))  {
				if (!($this->columns[$property->name] instanceof \ActiveMapper\DataTypes\Int)) {
					throw new \ActiveMapper\InvalidDataTypeException(
						"Autoincrement avaiable only for Int data type column [".$property->name."]"
					);
				} elseif ($property->name == $this->primaryKey)
					$this->primaryKeyAutoincrement = TRUE;
				else
					throw new \NotImplementedException("Auto increment for non primary key column not implemented");
			}
		}

		if (!$this->primaryKey)
			throw new \LogicException("Entity without primary key not supported");

		/************************************************** Entity name & Table name **************************************************p*v*/
		$pos = strrpos($entity, '\\');
		if ($pos === FALSE)
			$this->name = $entity;
		else
			$this->name = substr($entity, $pos + 1);

		if ($ref->hasAnnotation('tableName'))
			$this->tableName = $ref->getAnnotation('tableName');
		else
			$this->tableName = Tools::underscore(Tools::pluralize($this->name));
	}

	/**
	 * Load associations
	 *
	 * @return void
	 */
	protected function loadAssociations()
	{
		$annotations = ClassReflection::from($this->entity)->getAnnotations();
		if (isset($annotations['OneToOne']) && count($annotations['OneToOne']) > 0) {
			foreach ($annotations['OneToOne'] as $data) {
				$data = (array)$data;
				$assoc = new Associations\OneToOne($this->entity, $data[0],
					isset($data['mapped']) ? $data['mapped'] : TRUE,
					isset($data['name']) ? $data['name'] : NULL,
					isset($data['targetColumn']) ? $data['targetColumn'] : NULL,
					isset($data['sourceColumn']) ? $data['sourceColumn'] : NULL
				);
				$this->associations[$assoc->name] = $assoc;
				if ($assoc->sourceColumn != $this->primaryKey)
					$this->associationsKeys[] = $assoc->sourceColumn;
			}
		}
		if (isset($annotations['OneToMany']) && count($annotations['OneToMany']) > 0) {
			foreach ($annotations['OneToMany'] as $data) {
				$data = (array)$data;
				$assoc = new Associations\OneToMany($this->entity, $data[0],
					isset($data['name']) ? $data['name'] : NULL,
					isset($data['targetColumn']) ? $data['targetColumn'] : NULL,
					isset($data['sourceColumn']) ? $data['sourceColumn'] : NULL
				);
				$this->associations[$assoc->name] = $assoc;
				if ($assoc->sourceColumn != $this->primaryKey)
					$this->associationsKeys[] = $assoc->sourceColumn;
			}
		}
		if (isset($annotations['ManyToOne']) && count($annotations['ManyToOne']) > 0) {
			foreach ($annotations['ManyToOne'] as $data) {
				$data = (array)$data;
				$assoc = new Associations\ManyToOne($this->entity, $data[0],
					isset($data['name']) ? $data['name'] : NULL,
					isset($data['targetColumn']) ? $data['targetColumn'] : NULL,
					isset($data['sourceColumn']) ? $data['sourceColumn'] : NULL
				);
				$this->associations[$assoc->name] = $assoc;
				if ($assoc->sourceColumn != $this->primaryKey)
					$this->associationsKeys[] = $assoc->sourceColumn;
			}
		}
		if (isset($annotations['ManyToMany']) && count($annotations['ManyToMany']) > 0) {
			foreach ($annotations['ManyToMany'] as $data) {
				$data = (array)$data;
				$assoc = new Associations\ManyToMany($this->entity, $data[0],
					isset($data['mapped']) ? $data['mapped'] : TRUE,
					isset($data['name']) ? $data['name'] : NULL,
					isset($data['targetColumn']) ? $data['targetColumn'] : NULL,
					isset($data['sourceColumn']) ? $data['sourceColumn'] : NULL,
					isset($data['joinTable']) ? $data['joinTable'] : NULL,
					isset($data['joinTableTargetColumn']) ? $data['joinTableTargetColumn'] : NULL,
					isset($data['joinTableSourceColumn']) ? $data['joinTableSourceColumn'] : NULL
				);
				$this->associations[$assoc->name] = $assoc;
				if ($assoc->sourceColumn != $this->primaryKey)
					$this->associationsKeys[] = $assoc->sourceColumn;
			}
		}

		$this->isAssociationsLoaded = TRUE;
	}

	/**
	 * Get columns DataType objects
	 *
	 * @return array
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 * Has column
	 *
	 * @return bool
	 */
	public function hasColumn($name)
	{
		return isset($this->columns[$name]);
	}

	/**
	 * Get column DataType object by name
	 *
	 * @param string $name colum name
	 * @return ActiveMapper\DataTypes\IDataType
	 * @throws InvalidArgumentException
	 */
	public function getColumn($name)
	{
		if (!$this->hasColumn($name))
			throw new \InvalidArgumentException("Column '".$name."' not exist in '".$this->entity."' entity");

		return $this->columns[$name];
	}

	/**
	 * Get associations AssoctiationType objects
	 *
	 * @return array
	 */
	public function getAssociations()
	{
		if (!$this->isAssociationsLoaded)
			$this->loadAssociations();

		return $this->associations;
	}

	/**
	 * Has association
	 *
	 * @param string $name asscociation name
	 * @return bool
	 */
	public function hasAssociation($name)
	{
		if (!$this->isAssociationsLoaded)
			$this->loadAssociations();

		return isset($this->associations[$name]);
	}

	/**
	 * Get association AssociationType object by name
	 *
	 * @param string $name association name
	 * @return ActiveMapper\Assoctiations\IAssociation
	 * @throws InvalidArgumentException
	 */
	public function getAssociation($name)
	{
		if(!$this->hasAssociation($name))
			throw new \InvalidArgumentException("Association '".$name."' not exist in '".$this->entity."' entity");

		return $this->associations[$name];
	}

	/**
	 * Get associations key
	 *
	 * @return array
	 */
	public function getAssociationsKeys()
	{
		if (!$this->isAssociationsLoaded)
			$this->loadAssociations();
		
		return $this->associationsKeys;
	}
	
	/**
	 * Get primary key name
	 * 
	 * @return string|null
	 */
	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}

	/**
	 * Get entity table name
	 *
	 * @return string
	 */
	public function getTableName()
	{
		return $this->tableName;
	}

	/**
	 * Get entity name
	 *
	 * @return strin
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * To cache
	 *
	 * @return ActiveMapper\EntityMetadata
	 */
	public function toCache()
	{
		if (!$this->isAssociationsLoaded)
			$this->loadAssociations();

		return $this;
	}
}