<?php

require_once (dirname(__FILE__) . '\Adapter\AdjacencyList.php');
require_once (dirname(__FILE__) . '\Visitor\Html.php');

$list = array(
    
    array('id' => 1, 	'name' => 'Design Patterns',	'parent' => 0), 
    array('id' => 2, 	'name' => 'Creational',			'parent' => 1), 
    array('id' => 3, 	'name' => 'Structural',			'parent' => 1), 
    array('id' => 4, 	'name' => 'Behaviour',			'parent' => 1), 
    array('id' => 5, 	'name' => 'Abstract Factory',	'parent' => 2), 
    array('id' => 6, 	'name' => 'Factory Method',		'parent' => 2), 
    array('id' => 7, 	'name' => 'Prototype',			'parent' => 2), 
    array('id' => 8, 	'name' => 'Singleton',			'parent' => 2), 
    array('id' => 9, 	'name' => 'Adapter',			'parent' => 3), 
    array('id' => 10, 	'name' => 'Composite',			'parent' => 3), 
    array('id' => 11, 	'name' => 'Decorator',			'parent' => 3), 
    array('id' => 12, 	'name' => 'Facade',				'parent' => 3), 
    array('id' => 13, 	'name' => 'Proxy',				'parent' => 3), 
    array('id' => 14, 	'name' => 'Command',			'parent' => 4), 
    array('id' => 15, 	'name' => 'Iterator',			'parent' => 4), 
    array('id' => 16, 	'name' => 'Strategy',			'parent' => 4), 
    array('id' => 17, 	'name' => 'Observer',			'parent' => 4), 
    array('id' => 18, 	'name' => 'Visitor',			'parent' => 4), 
    array('id' => 19, 	'name' => 'Implemented',		'parent' => 9), 
    array('id' => 20, 	'name' => 'Implemented',		'parent' => 10), 
    array('id' => 21, 	'name' => 'Implemented',		'parent' => 15), 
    array('id' => 22, 	'name' => 'Implemented',		'parent' => 18)
);

$tree = new Tree_Adapter_AdjacencyList($list);

echo $tree->fetchChildren(1)
          ->accept(new Tree_Visitor_Html());	