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

class Ingot_JQuery_JqGrid_Column_Decorator_Hebrew extends Ingot_JQuery_JqGrid_Column_Decorator_Abstract
{
    private $_needTranslation = false;
    private $_inCodePage = "";
    private $_outCodePage = "";

    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct(Ingot_JQuery_JqGrid_Column $column, $options = array())
    {
    	parent::__construct($column, $options);
    }
    
    public function setTranslation($boolValue = false){
    	if (!empty($boolValue)){
    		$this->_needTranslation = true;
    	} else {
    		$this->_needTranslation = false;
    	}
    	return;
    }
    
    public function getTranslation(){
    	return $this->_needTranslation;
    }
    
    // :TODO Add Code Page Set and Get for Input and Output

    /**
	 * Decorate column to display Currency
	 * 
	 * @return void
	 */
    public function decorate()
    {
    	
    }
    
    public function cellValue($row){
    	$strCellValue = parent::cellValue($row);
    	$strCellValue = html_entity_decode($strCellValue);
    	if ($this->getTranslation()){
    		$strCellValue = iconv("cp1255","UTF-8",$strCellValue); 
    	}
    	return $strCellValue;
    	
    }
}