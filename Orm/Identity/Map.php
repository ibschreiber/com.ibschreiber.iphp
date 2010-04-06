<?php

/**
 * Object Relational Mapper
 * 
 * An Identity Map keeps a record of all objects that have been read from the 
 * database in a single business transaction. Whenever you want an object, 
 * you check the Identity Map first to see if you already have it.
 *
 * @category  Orm
 * @package   Identity Map
 * @copyright Copyright (c) 2007 Warrant Group Limited
 */

class Orm_Identity_Map {
	
	protected $_map = array();
	
	/*
	 * Check if the identity map already has the object, if true
	 * return the primary key
	 *
	 * @param $obj Orm_Domain_Object
	 * @return int
	 */
	
	public function hasObject (Orm_Domain_Object $obj) {

		if ($obj->getId() != null) {
			return $obj->getId();
		}
		
		return false;
	}
	
	/*
	 * Check if the identity map has a record of the object by identifying 
	 * if primary key exists.
	 *
	 * @param $id primarykey
	 * @return boolean
	 */
	
	public function has ($id) {
		return isset($this->_map[$id]);
	}
	
	/*
	 * Add object to identity map
	 *
	 * @param $obj Orm_Domain_Object
	 * @return void
	 */
	
	public function add (Orm_Domain_Object $obj) {
		
		if ($obj->getId() === null) {
			throw new Orm_Identity_Map_Exception('Cannot save object into IdentityMap as no id key has been set');
		}
		
		$this->_map[$obj->getId()] = $obj;
	}
	
	/*
	 * Remove object from identity map
	 *
	 * @param $id primary key
	 */
	
	public function remove ($id) {
		unset($this->_map[$id]);
	}
	
	/*
	 * Retrieve object from identity map by primary key
	 *
	 * @param $id primary key
	 * @return Orm_Domain_Object
	 */
	
	public function get ($id) {

		if (!isset($this->_map[$id])) {
			throw new Orm_Identity_Map_Exception('Cannot find an object in the IdentityMap with id key ' . $id);
		}
		
		return $this->_map[$id];
	}
	
}