<?php

/**
 * Object Relational Mapper
 * 
 * The Data Mapper is a layer that separates the in-memory objects from 
 * the database. Its responsibility is to transfer data between the two and 
 * also to isolate them from each other. 
 *
 * @category  	Orm
 * @package   	Mapper
 * @subpackage 	Abstract
 * @copyright 	Copyright (c) 2007 Warrant Group Limited
 */

abstract class Orm_Mapper_Abstract {

	protected $_identityMap;
	
	const findStatememt = 'SELECT * FROM `%s` WHERE `%s` = %s ';
	const insertStatement = 'INSERT INTO `%s` (%s) VALUES (%s)';
	const updateStatement = 'UPDATE `%s` SET %s WHERE `%s` = %s';
	const deleteStatement = 'DELETE FROM `%s` WHERE `%s` = %s';
	
	protected function __construct() {
		$this->_identityMap = new Orm_Identity_Map ( );
	}
	
	/**
	 * Factory method which returns the mapper for a 
	 * specified name

	 * @return Orm_Mapper
	 */
	
	public static function create($name) {
		
		// If we don't have a mapper loaded try to load it
		if (! isset ( self::$_mappers [$name] )) {
			$mapper = Orm_Loader::loadMapperClass ( $name );
			self::$mappers [$name] = $mapper;
		}
		
		return self::$mappers [$name];
	}
	
	/**
	 * Populate domain object from array, by assigning row field names
	 * and values to each member variable of the domain object.
	 * 
	 * @return Orm_Domain_Object
	 */
	
	public function load($row, $obj) {
		
		// Assign the row by field name to the domain object 
		// member variable
		
		if (count ( $row ) > 0) {
			foreach ( $row as $field => $value ) {
				$field = Orm_Inflector::camelize ( $field );
				if ($field == 'id') {
					$obj->setId ( $row ['id'] );
				} else {
					$obj->$field = $value;
				}
			}
		}
		
		return $obj;
	
	}
	
	abstract public function findById($id);
	abstract public function insert(Orm_Domain_Object $obj);
	abstract public function update(Orm_Domain_Object $obj);
	abstract public function delete($obj);
	
	/**
	 * The save method will determine from the identity map whether to
	 * insert or update the transaction.
	 * 
	 * return Orm_Domain_Object
	 */
	
	public function save(Orm_Domain_Object $obj) {
		
		// If the object already is in our IdentityMap, update it
		if ($this->_identityMap->hasObject ( $obj )) {
			$this->update ( $obj );
			return $obj;
		}
		
		$this->insert ( $obj );
		$this->_identityMap->add ( $obj );
		
		return $obj;
	}
	
	/**
	 * Helper method to evaluate a database row and determine from the 
	 * primary key if the object exists in the identity map, or whether 
	 * a new entry should be added.
	 * 
	 * return Orm_Domain_Object
	 */
	
	protected function _loadIdentityMap(array $row) {
		
		$id = $row [$this->getPrimaryKey ()];
		
		// If we dont already have the object create it
		// and save it in the identity map

		if (! $this->_identityMap->has ( $id )) {
			
			$domain = $this->_getDomainObjectName ();
			Orm_Loader::loadClass ( $domain );
			$obj = $this->load ( $row, new $domain ( ) );
			
			$this->_identityMap->add ( $obj );
		}
		
		return $this->_identityMap->get ( $id );
	}

}