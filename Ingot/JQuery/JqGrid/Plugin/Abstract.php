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
	 * Set a single option
	 * 
	 * @return Ingot_JQuery_JqGrid_Plugin_Abstract
	 */
	public function setOption($name, $value) {
		if (is_array ( $value )) {
			if (! empty ( $this->_options [$name] )) {
				$this->_options [$name] = array_merge_recursive ( ( array ) $this->_options [$name], $value );
			} else {				
				$this->_options [$name] = $value;
			}
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
		
		$objGrid = $this->getGrid ();
		return $objGrid->encodeJsonOptions ( $arrData );
		
		return ZendX_JQuery::encodeJson ( $arrData );
	}
	
	abstract public function preResponse();
	abstract public function postResponse();
	abstract public function preRender();
	abstract public function postRender();
}