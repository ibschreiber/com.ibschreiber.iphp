<?php

/**
 * Plugin Abstract
 *
 * @package Ingot_JQuery_JqGrid
 * @copyright Copyright (c) 2005-2009 Warrant Group Ltd. (http://www.warrant-group.com)
 * @author Andy Roberts
 */

abstract class Ingot_JQuery_JqGrid_Plugin_Abstract {
	/**
	 * Grid Instance
	 * 
	 * @var Ingot_JQuery_JqGrid
	 */
	protected $_grid;
	
	/**
	 * Grid Data Instance
	 * 
	 * @var object
	 */
	protected $_gridData;
	
	/**
	 * View Instance
	 * 
	 * @var Zend_View
	 */
	protected $_view;
	
	/**
	 * Default Plugin Config
	 * 
	 * @var array
	 */
	protected $_defaultConfig = array ();
	
	/**
	 * Plugin Name
	 * 
	 * @var $_defaultPlugin string
	 */
	protected $_defaultPlugin = "";
	
	/**
	 * Plugin options
	 * 
	 * @var array
	 */
	
	private $_options = array ();
	/**
	 * Set View Instance
	 * 
	 * @param $view
	 */
	public function setView($view) {
		$this->_view = $view;
	}
	
	/**
	 * Set Grid Instance 
	 * 
	 * @param $grid Ingot_JQuery_JqGrid
	 * @return void
	 */
	public function setGrid(Ingot_JQuery_JqGrid $grid) {
		$this->_grid = $grid;
	}
	
	/**
	 * Get Grid Instance
	 *
	 * @return Ingot_JQuery_JqGrid
	 */
	public function getGrid() {
		return $this->_grid;
	}
	
	/**
	 * Set an instance of the grid data structure
	 *
	 * @param object $data
	 * @return void
	 */
	public function setGridData($data) {
		$this->_gridData = $data;
	}
	
	/**
	 * Get an instance of the grid data structure
	 * 
	 * @return object
	 */
	public function getGridData() {
		return $this->_gridData;
	}
	
	/**
	 * Add HTML to plugin
	 *
	 * @param $html HTML string
	 */
	public function addHtml($html) {
		$this->_view->jqGridPluginBroker ['html'] [] = $html;
	}
	
	/**
	 * Add javascript to plugin for onload
	 *
	 * @param $js javascript string
	 */
	public function addOnLoad($js) {
		$this->_view->jqGridPluginBroker ['onload'] [] = $js;
	}
	
	/**
	 * Add javascript to plugin
	 *
	 * @param $js javascript string
	 */
	public function addJavascript($js, $onload = false) {
		if ($onload == true) {
			return $this->addOnLoad ( $js );
		}
		
		$this->_view->jqGridPluginBroker ['js'] [] = $js;
	}
	
	/**
	 * Sets options
	 *
	 * @param array $options
	 * @return Ingot_JQuery_JqGrid_Plugin_Abstract
	 */
	public function setOptions(array $options = array()) {
		
		foreach ( $options as $k => $v ) {
			$this->setOption ( $k, $v );
		}
		return $this;
	}
		
	/**
	 * Set a single column option
	 * 
	 * @return Ingot_JQuery_JqGrid_Plugin_Abstract
	 */
	public function setOption($name, $value) {
	
	
		$arrUnEscapeList = array_merge ( (array)$this->getMethods(), (array)$this->getEvents() );
		
		if (in_array ( $name, $arrUnEscapeList, true )) {
			$this->_options [$name] = new Zend_Json_Expr($value);			
		} else {
			$this->_options [$name] = $value;
		}
		return $this;
	}
	
	
	/**
	 * Get a single option
	 * 
	 * @return mixed
	 */
	public function getOption($name) {
		if (array_key_exists ( $name, $this->_options )) {
			return $this->_options [$name];
		} else {
			return false;
		}
	}
	
	/**
	 * Get a single option
	 * 
	 * @return mixed
	 */
	public function getOptions() {
		return $this->_options;
	
	}
	
	protected function getConfig() {
		
		$arrData = $this->_defaultConfig;
		
		if (! empty ( $this->_defaultPlugin )) {
			$arrConfigData = $this->getOption ( $this->_defaultPlugin );
			if (! empty ( $arrConfigData )) {
				$arrData = array_merge ( $arrData, ( array ) $arrConfigData );
			}
		}
		
		$objGrid = $this->getGrid();
		return $this->encodeJsonOptions($arrData);
	}
	
	
	public function encodeJsonOptions($arrProperties) {
						
		$strOptions = '';
		
		if ($this->getGrid()->isUseCustonJson() ){
		
			$arrUnEscapeList = array_merge ( (array)$this->getMethods(), (array)$this->getEvents() );
		
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
						$strOptions .= '"' . $strPropertyKey . '":' . ZendX_JQuery::encodeJson ( $mixProperty );
					}
				
				}
			}
			
			$strOptions = "{" . $strOptions . "}";
		
		} else {
			$strOptions =  ZendX_JQuery::encodeJson ( $arrProperties );
		} 
		return $strOptions;
	}
	
	
	
	abstract public function preResponse();
	abstract public function postResponse();
	abstract public function preRender();
	abstract public function postRender();
	
	abstract public function getMethods();
	abstract public function getEvents();
}