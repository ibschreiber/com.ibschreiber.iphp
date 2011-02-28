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
        $this->_options = $options;
		parent::__construct($column);   
		
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
        
//    searchoptions:{dataInit:function(el){$(el).datepicker({dateFormat:'yy-mm-dd'});} }

		if (!empty($this->_options['datepicker'])){
			$arrSearchOptions= array(); //
			$arrSearchOptions["dataInit"] =  new Zend_Json_Expr("function(el){\$(el).datepicker({dateFormat:' ".$this->_options['datepicker']." ',  onClose: function(dateText, inst) { var sgrid = $('#".$this->getGridId()."')[0]; sgrid.triggerToolbar(); }});}");
			unset($this->_options['datepicker']);
		}
        
        $this->_column->setOption('formatter', 'date');
        $this->_column->setOption('formatoptions', $this->_options);
        $this->_column->setOption('searchoptions', $arrSearchOptions);
    }
	
	public function cellValue($row){
		$strRawCellValue = parent::cellValue($row);
		
		$strTime = mktime(0,0,1,substr($strRawCellValue,4,2),substr($strRawCellValue,6,2),substr($strRawCellValue,0,4));
		
		if (!empty($this->_options['srcformat'])){
			$strDateFormat = $this->_options['srcformat'];
		} else {
			$strDateFormat = 'Y-m-d H:i:s';
		}
		
		return date($strDateFormat, $strTime );	
	}
	
	public function unformatValue($strValue){
		$strValue = trim($strValue);
		switch ($this->_options['datepicker']){
			case "dd/mm/yy":
			default;				
				$strTimestamp = mktime(0,0,1,substr($strValue,3,2),substr($strValue,0,2),substr($strValue,6,4));
			
			break;		
		} 
    	return date($this->_options['informat'], $strTimestamp );
    }
    
}