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
    protected $_primary;
    
    public function __construct( array $field )
    {
        $this->_name	 = $field['COLUMN_NAME'] );
        $this->_primary	 = $field['PRIMARY'];
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
     * Determine if the field is part of a primary key
     *
     * @return boolean
     */
    public function isPrimary()
    {
        return $this->_primary;
    }
}