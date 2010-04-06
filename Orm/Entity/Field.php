<?php

/**
 * Object Relational Mapper
 * 
 * An object to encapsulate the fields of a database entity
 *
 * @category  	Orm
 * @package   	Entity
 * @subpackage 	Field
 * @copyright 	Copyright (c) 2007 Warrant Group Limited
 */

class Orm_Entity_Field
{
    protected $_name;
    protected $_original;
    protected $_primary;
    
    public function __construct( $name, array $details )
    {
        $this->_name	 = $name;
        $this->_original = $details['COLUMN_NAME'];
        $this->_primary	 = $details['PRIMARY'];
    }

    /**
     * Gets the name of the field
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * Gets the original name
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->_original;
    }
    
    /**
     * Determine if the field is part of a primary key
     *
     * @return boolean
     */
    public function isPrimary()
    {
        return $this->_primary;
    }
}