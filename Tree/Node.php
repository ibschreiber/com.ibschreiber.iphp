<?php

require_once dirname(__FILE__) . '\Composite\Interface.php';
require_once dirname(__FILE__) . '\Composite\Iterator.php';
require_once dirname(__FILE__) . '\Visitor\Visitable.php';

/**
 * Tree_Node represents a node in a tree including methods which act
 * upon nodes, for example fetching nodes and attaching children
 * to parent nodes.
 *
 * Accepts Tree_Visitor_Interface for formatting the layout of each
 * node, as per the visitor pattern.
 * 
 * @package		Tree
 * @subpackage	Composite
 * @author		andy.roberts
 */
class Tree_Node implements Tree_Composite_Interface, Tree_Visitor_Visitable
{
    private $_id;
    private $_name;
    private $_parent;
    private $_children = array();

    /*
     * Constructor
     * 
     * @param array $item
     */
    public function __construct($item)
    {
        $this->_id = $item['id'];
        $this->_name = $item['name'];
        $this->_parent = $item['parent'];
    }

    /**
     * Returns whether the node has children.
     *
     * @return bool
     */
    public function hasChildren()
    {
        return (count($this->_children) > 0) ? true : false;
    }

    /**
     * Returns the children of the node
     *
     * @return array
     */
    public function getChildren()
    {
        return $this->_children;
    }

    /**
     * Returns the parent node of the node
     *
     * @return int
     */
    public function getParent()
    {
        return $this->_parent;
    }

    /**
     * Returns the id of the node
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Returns the name of the node
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Attach the node $node as child of the node.
     *
     * @param Tree_Node $node
     */
    public function attach(Tree_Composite_Interface $node)
    {
        $this->_children[$node->getId()] = $node;
    }

    /**
     * Implements the accept method for visiting.
     *
     * @param Tree_Visitor_Interface $visitor
     */
    public function accept(Tree_Visitor_Interface $visitor)
    {
        $visitor->visit($this);
        
        foreach (new Tree_Composite_Iterator($this) as $node) {
            $node->accept($visitor);
        }
        
        return $visitor;
    }
}