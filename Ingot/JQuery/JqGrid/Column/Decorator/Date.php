<?php

/**
 * @see Ingot_JQuery_JqGrid_Column_Decorator_Abstract
 */
require_once 'Ingot/JQuery/JqGrid/Column/Decorator/Abstract.php';

/**
 * Decorate a column which contains a date
 * 
 * @package Ingot_JQuery_JqGrid
 * @copyright Copyright (c) 2005-2009 Warrant Group Ltd. (http://www.warrant-group.com)
 * @author Andy Roberts
 */

class Ingot_JQuery_JqGrid_Column_Decorator_Date extends Ingot_JQuery_JqGrid_Column_Decorator_Abstract
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
	 * Decorate column to display URL links
	 * 
	 * @return void
	 */
    public function decorate()
    {
        
        if (count($this->_options) == 0) {
            $this->_options['srcformat'] = 'Y-m-d H:i:s';
            $this->_options['newformat'] = 'l, F d, Y';
        }
        
        $arrOptions = $this->_options;
        
        if (!empty($arrOptions['informat'])){
        	unset($arrOptions['informat']);
        }
        
        $this->_column->setOption('formatter', 'date');
        $this->_column->setOption('formatoptions', $arrOptions);
    }
    
    public function cellValue($arrRow){
    	$strValue = parent::cellValue($arrRow);
    	
        $arrOptions = $this->_options;
        
        if (!empty($arrOptions['informat'])){
        	switch ($arrOptions['informat']) {
        		case 'YYYYMM':
        			
        			break;
        		
        		case 'timestamp':
        		default:
        			// Do nothing the data is allready in the correct format
      
        		break;
        	}
        	
        	$strValue = date($arrOptions['srcformat'],$strValue);
        }
        
        return $strValue;
    	    	
    }
    
}