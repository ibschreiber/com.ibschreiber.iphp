<?php

/**
 * Interface for implementations that want to process a tree using 
 * the visitor design pattern.
 * 
 * @category	Tree
 * @package		Visitor
 * @author		andy.roberts
 */
interface Tree_Visitor_Interface
{
    /**
     * Each each node in the tree is visited once.
     *
     * @param  Tree_Visitor_Visitable $visitable
     * @return bool
     */
    public function visit(Tree_Visitor_Visitable $visitable);
}

