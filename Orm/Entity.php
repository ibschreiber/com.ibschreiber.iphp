<?php
/**
 * Object Relational Mapper
 * 
 * Entity
 *
 * @category  Orm
 * @package   Entity
 * @copyright Copyright (c) 2007 Warrant Group Limited
 */

abstract class Orm_Entity {
	
	protected $_id;

	/**
	 * Allow overloading which is invoked when interacting with methods 
	 * that have not been declared. 
	 * 
	 * @return void
	 */
	
	public function __call( $method, $args ) {
		
		if (preg_match( "/set(.*)/", $method, $found )) {
			$var = Orm_Inflector::lcfirst($found[1]);
			if (property_exists($this, $var)){
				$this->{$var} = $args[0];
			}
		}
		elseif(preg_match( "/get(.*)/", $method, $found )) {	
			$var = Orm_Inflector::lcfirst($found[1]);
			if (property_exists($this, $var)){
				return $this->{$var};
			}
		}
		
		return;
	}
	
	/**
	 * Set the primary key, of which must be an immutable object is an object whose state cannot 
	 * be modified after it is created
	 *
	 * @param int $id primary key
	 */
	
	public function setId ($id) {

		if ($this->_id !== null) {
			throw new Orm_Domain_Exception('The primary key (id) field cannot be set as its immutable');
		}

		$this->_id = $id;
	}
	
	/**
	 * Get primary key
	 *
	 * @param int $id primary key
	 * @return int 
	 */

	public function getId () {
		return $this->_id;
	}
	
}