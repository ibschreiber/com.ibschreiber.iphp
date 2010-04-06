<?php
/**
 * Interface for visitable tree nodes that can accept the
 * Tree_Visitor_Interface for processing implementations
 * using the visitor design pattern.
 *
 * @category   Tree
 * @package    Visitor
 * @author	   andy.roberts
 */
interface Tree_Visitor_Visitable
{
    /**
     * Accepts the visitor.
     *
     * @param Tree_Visitor_Interface $visitor
     */
    public function accept(Tree_Visitor_Interface $visitor);
}

