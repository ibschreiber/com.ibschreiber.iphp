<?php
/*
 * Interface to represent the behaviour of each node in an 
 * composite tree structure.
 * 
 * @package 	Tree
 * @subpackage 	Composite
 * @author 		andy.roberts
 */
interface Tree_Composite_Interface
{
    public function getId();
    public function hasChildren();
    public function getChildren();
    public function attach(Tree_Composite_Interface $node);
}