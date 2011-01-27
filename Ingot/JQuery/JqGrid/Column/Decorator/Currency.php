<?php

/**
 * @see Ingot_JQuery_JqGrid_Column_Decorator_Abstract
 */
require_once 'Ingot/JQuery/JqGrid/Column/Decorator/Abstract.php';

/**
 * Decorate a column which contains a currency
 * 
 * @package Ingot_JQuery_JqGrid
 * @copyright Copyright (c) 2005-2009 Warrant Group Ltd. (http://www.warrant-group.com)
 * @author Alex (Shurf) Frenkel
 */

class Ingot_JQuery_JqGrid_Column_Decorator_Currency extends Ingot_JQuery_JqGrid_Column_Decorator_Abstract
{
    protected $_options = array();

    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct($column, $options = array())
    {
        $this->_column = $column;
        $this->_options = $options;
        
        $this->decorate();
    }

    /**
	 * Decorate column to display Currency
	 * 
	 * @return void
	 */
    public function decorate()
    {
    	        
        $this->_column->setOption('formatter', 'date');
        if (!empty($this->_options)){
        	     $this->_column->setOption('formatoptions', $this->_options);
        }   
    }
}