<?php
/**
 * Object Relational Mapper
 * 
 * An object to encapsulate a collection of fields of a 
 * database entity
 *
 * @category  	Orm
 * @package   	Entity
 * @subpackage 	Collection
 * @copyright 	Copyright (c) 2007 Warrant Group Limited
 */

class Orm_Entity_Collection implements Iterator, Countable {
	
	protected $_fields = array ();
	protected $_primary = "";
	
	public function __construct($metadata) {
		$this->load ( $metadata );
	}
	
	/**
	 * Populate entity with its associated metadata from the database table
	 *
	 * @param array $metadata
	 * @return void
	 */
	public function load($metadata) {
		
		foreach ( $metadata as $fields ) {
		    
			$field = new Orm_Entity_Field ( $fields );
			$this->_fields [] = $field;
			
			if ($field->isPrimary ()) {
				$this->_primary [] = $field;
			}
		}
	}
	
	/**
	 * Gets a field by name
	 *
	 * @param string $name
	 */
	public function getField($name) {
		if (! in_array ( $name, array_keys ( $this->_fields ) )) {
			throw new Orm_Domain_Object_Exception ( $name . ' is not a member of the ' . $this->getName () . ' class' );
		}
		return $this->_fields [$name];
	}
	
	/**
	 * Gets the names of the fields for the entity
	 *
	 * @return array field names
	 */
	public function getFieldNames() {
		return array_keys ( $this->_fields );
	}
	
	/**
	 * Gets the fields
	 *
	 * @return array field objects
	 */
	public function getFields() {
		return $this->_fields;
	}
	
	/**
	 * Return the total number of fields
	 * 
	 * @return int
	 */
	
	public function count() {
		return count ( $this->users );
	}
	
	/**
	 * Implement Iterator to tranverse through field names
	 */
	
	public function rewind() {
		reset ( $this->_fields );
	}
	
	public function current() {
		$var = current ( $this->_fields );
		return $var;
	}
	
	public function key() {
		$var = key ( $this->_fields );
		return $var;
	}
	
	public function next() {
		$var = next ( $this->_fields );
		return $var;
	}
	
	public function valid() {
		$var = $this->current () !== false;
		return $var;
	}

}