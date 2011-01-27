<?php

/**
 * @see Ingot_JQuery_JqGrid_Plugin_Abstract
 */
require_once 'Ingot/JQuery/JqGrid/Plugin/Abstract.php';

/**
 * Display a search filter on each column
 *
 * @package Ingot_JQuery_JqGrid
 * @copyright Copyright (c) 2005-2009 Warrant Group Ltd. (http://www.warrant-group.com)
 * @author Andy Roberts
 */

class Ingot_JQuery_JqGrid_Plugin_ToolbarFilter extends Ingot_JQuery_JqGrid_Plugin_Abstract
{
    protected $_options;
	
	protected static $_arrEvents = array("beforeSearch",	"afterSearch",	"beforeClear",	"afterClear");

    public function __construct($options = array())
    {
        $this->_options = $options;
    }

    public function preRender()
    {
        
        if (! isset($this->_options['stringResult'])) {
            $this->_options['stringResult'] = true;
        }
        
        $js = sprintf('%s("#%s").filterToolbar(%s);', 
                ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(), 
                $this->getGrid()->getId(), 
                $this->encodeJsonOptions($this->_options));
        
        $this->addOnLoad($js);
        
        $columns = $this->getGrid()->getColumns();
        
        foreach ($columns as $column) {
            $column->setOption('search', true);
        }
    }

    public function postRender()
    {    // Not implemented
    }

    public function preResponse()
    {    // Not implemented
    }

    public function postResponse()
    {    // Not implemented
    }
	
	public function encodeJsonOptions($arrProperties) {
		
		$arrUnEscapeList = array_merge ( Ingot_JQuery_JqGrid_Plugin_ToolbarFilter::$_arrEvents );
		
		$strOptions = '';
		
		// Iterate over array
		foreach ( ( array ) $arrProperties as $strPropertyKey => $mixProperty ) {
			
			if (! empty ( $strOptions )) {
				$strOptions .= ", ";
			}
			// Check that it's not one of the elements that needs escaiping 	
			if (in_array ( $strPropertyKey, $arrUnEscapeList, true )) {
				// This value does not need escaiping
				$strOptions .= '"' . $strPropertyKey . '":' . $mixProperty;
			} else {
				if (is_array ( $mixProperty )) {
					// Recursive call
					$strOptions .= '"' . $strPropertyKey . '":' . $this->encodeJsonOptions ( $mixProperty );
				} else {
					// @TODO add option to switch what JSON to use.
					$strOptions .= '"' . $strPropertyKey . '":' . ZendX_JQuery::encodeJson ( $mixProperty );
					//$strOptions .= '"' . $strPropertyKey . '":' . custom_json::encode ( $mixProperty );
				}
			
			}
		}
		
		$strOptions = "{" . $strOptions . "}";
		
		return $strOptions;
	}
}