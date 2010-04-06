<?php

/**
 * Object Relational Mapper
 * 
 * The class loader for the Orm layer
 *
 * @category  Orm
 * @package   Loader
 * @copyright Copyright (c) 2007 Warrant Group Limited
 */

class Orm_Loader
{
	/**
     * Store file paths of model classes
     * 
     * @var array
     */
	static protected $_paths = array();

	/**
     * Adds a path to where class files for where models can be found
     *
     * @param string $path
     */
	public static function addPath( $path )
	{
		$path        = rtrim($path, '/');
		$path        = rtrim($path, '\\');
		$path       .= DIRECTORY_SEPARATOR;

		if ( @!is_dir($path) ) {
			require_once 'Mapper/Exception.php';
			throw new Orm_Mapper_Exception("The path '$path' does not exist'");
		}

		self::$_paths[$path] = $path;
	}

	/**
     * Tries to load the class in one of the paths defined for entities
     *
     * @param string $className
     * @return string the class name loaded
     */
	public static function loadClass( $className )
	{
		if ( class_exists($className, false) ) {
			return $className;
		}
		self::loadFile($className . '.php', self::$_paths, true);

		if (!class_exists($className,false)) {
			throw new Orm_Mapper_Exception('Invalid class ("' . $className . '")');
		}

		return $className;
	}

	/**
     * Loads the mapper class for the class name given
     * 
     * @param string $className the name of the entity class
     * @return string the class name
     */
	public static function loadMapperClass( $className )
	{
		self::loadClass($className);

		$className = $className . 'Mapper';
		self::loadClass($className);
		
		$mapper = new $className();

		if (!($mapper instanceof Orm_Mapper_Abstract)) {
			throw new Orm_Mapper_Exception("'" . $className . "' is not a instance of Orm_Mapper_Abstract");
		}

		return $className;
	}
	
	/**
	 * Loads a PHP file.
	 *
	 * @param  string        $filename
	 * @param  string|array  $dirs
	 * @return boolean
	 */

	public static function loadFile($filename, $dirs = null, $once = false)
	{
		return Zend::loadFile($filename, $dirs, $once);
	}

}