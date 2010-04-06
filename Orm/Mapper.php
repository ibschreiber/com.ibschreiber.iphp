<?php

/**
 * Object Relational Mapper
 * 
 * Objects and relational databases have different mechanisms for 
 * structuring data. Many parts of an object, such as inheritance, 
 * are not present in relational databases. When you build an object 
 * model with a lot of business logic its nice to use these OOP 
 * features to better organise the data and the behavior that 
 * goes with it. 
 * 
 * This can lead to an object relational impedence mismatch where 
 * the object schema and the relational schema don't match up and 
 * lead to a rather complicated set of conceptual and technical 
 * difficulties.
 * 
 * The Data Mapper patterns helps by acting as a layer that moves 
 * data between objects and a database while keeping them independent 
 * of each other and the mapper itself.
 *
 * @category  Orm
 * @package   Mapper
 * @copyright Copyright (c) 2007 Warrant Group Limited
 */

abstract class Orm_Mapper extends Orm_Mapper_Abstract
{

    protected static $_db;

    /**
     * The class name of the domain object
     * 
     * @var string
     */
    
    protected $_domain = "";

    /**
     * The name of the table, defaults to domain object name
     * 
     * @var string
     */
    
    protected $_table = "";

    /**
     * The field names of the table, the primary keys defaults 
     * to the first field
     * 
     * @var string
     */
    
    protected $_metaData = array();

    /**
     * The primary key
     * 
     * @var string
     */
    
    protected $_primaryKey = array();

    public function __construct($db)
    {
        parent::__construct();
        
        $this->_loadConnection($db);
        $this->_findMetaDataFromTable();
    }

    /**
     * Gets the primary key of table
     * 
     * @return string The table name
     */
    
    public function getPrimaryKey()
    {
        if (! $this->_primaryKey) {
            $this->_primaryKey = $this->_metaData[0]['COLUMN_NAME'];
        }
        
        return $this->_primaryKey;
    }

    /**
     * Gets the table name from the domain object
     * 
     * @return string The table name
     */
    
    public function getTableName()
    {
        if (! $this->_table) {
            $this->_table = strtolower(Orm_Inflector::underscore($this->_getDomainObjectName()));
        }
        
        return $this->_table;
    }

    /**
     * Finds object based on primary key
     * 
     * @return Orm_Domain_Object
     */
    
    public function findById($id)
    {
        
        // Does ID already exists in our IdentityMap?
        if ($this->_identityMap->has($id)) {
            return $this->_identityMap->get($id);
        }
        
        $sql = sprintf(self::findStatememt, $this->getTableName(), $this->getPrimaryKey(), self::$_db->quote($id));
        
        self::$_db->query($sql);
        $row = self::$_db->fetchRow();
        $domain = $this->_getDomainObjectName();
        
        # If row is false, return false
        if (($row === false) || (count($row) == 0)) {
            return new $domain();
        }
        
        return $this->_loadIdentityMap($row);
    }

    /**
     * Creates and executes an insert statement
     * 
     * @param $obj Orm_Domain_Object
     * @return string
     */
    
    public function insert(Orm_Domain_Object $obj)
    {
        
        $fields = array();
        $values = array();
        
        $fieldCollection = new Orm_Entity_Collection($this->_findMetaDataFromTable());
        
        foreach ($fieldCollection->getFieldNames() as $field) {
            $fields[] = $field;
            $values[] = self::$_db->quote($obj->{Orm_Inflector::camelize($field)});
        }
        
        $sql = sprintf(self::insertStatement, $this->getTableName(), implode($fields, ', '), implode($values, ', '));
        self::$_db->query($sql);
        
        $obj->setId(self::$_db->lastInsertId());
        $this->_identityMap->add($obj);
    }

    /**
     * Creates and executes an update statement
     * 
     * @param $obj Orm_Domain_Object
     * @return string
     */
    
    public function update(Orm_Domain_Object $obj)
    {
        
        $stmt = array();
        
        $fieldCollection = new Orm_Entity_Collection($this->_findMetaDataFromTable());
        
        foreach ($fieldCollection->getFieldNames() as $field) {
            $stmt[] = sprintf('`%s` = %s', $field, self::$_db->quote($obj->{'get' . ucfirst(Orm_Inflector::camelize($field))}()));
        }
        
        $sql = sprintf(self::updateStatement, $this->getTableName(), implode($stmt, ', '), $this->getPrimaryKey(), $obj->{'get' . ucfirst(Orm_Inflector::camelize($this->getPrimaryKey()))}());
        self::$_db->query($sql);
    }

    /**
     * Delete statement
     * 
     * @param int $id primary key
     * @return string
     */
    
    public function delete($id)
    {
        
        if ($id instanceof Orm_Domain_Object) {
            $id = $id->getId();
        }
        
        # Execute our generic find statement and fetch the row
        $sql = sprintf(self::deleteStatement, $this->getTableName(), $this->getPrimaryKey(), self::$_db->quote($id));
        self::$_db->query($sql);
        
        # Remove any record from identity map
        $this->_identityMap->remove($id);
        
        return $id;
    }

    /**
     * Load a connection to the database
     *
     * @return void
     */
    
    protected function _loadConnection($db)
    {
        self::$_db = $db;
        
        if (! self::$_db instanceof Zend_Db_Adapter_Abstract) {
            throw new Orm_Mapper_Exception('A database connection has not been defined, and must be an instance of Zend_Db_Adapter_Abstract.');
        }
    }

    /**
     * Finds the meta data from table
     * 
     * @return string The table name
     */
    
    protected function _findMetaDataFromTable()
    {
        if (! $this->_metaData) {
            $this->_metaData = self::$_db->describeTable($this->getTableName());
        }
        
        return $this->_metaData;
    }

    /**
     * Gets the class name of the domain object
     *
     * @return string  The class name of the domain object
     */
    
    protected function _getDomainObjectName()
    {
        if (! $this->_domain) {
            $this->_domain = substr(get_class($this), 0, - 6);
        }
        
        return $this->_domain;
    }
}