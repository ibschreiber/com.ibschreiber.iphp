<?php
/**
 * An iterator which can be used to iterate over all the nodes in a 
 * composite tree structure.
 *
 * @category	Tree
 * @package		Composite
 * @author		andy.roberts
 */
class Tree_Composite_Iterator implements RecursiveIterator
{
    /**
     * Holds the composite nodes of this list.
     *
     * @var array
     */
    private $_node;

    /**
     * Constructs an composite Tree_Node object
     *
     * @param Tree_Node $node
     */
    public function __construct(Tree_Node $node)
    {
        $this->_node = $node->getChildren();
    }

    /**
     * Rewinds the internal pointer back to the start of the node list
     * 
     * @return void;
     */
    public function rewind()
    {
        reset($this->_node);
    }

    /**
     * Returns the data belonging to the current node
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->_node);
    }

    /**
     * Returns the node ID of the current node.
     *
     * @return string
     */
    public function key()
    {
        return key($this->_node);
    }

    /**
     * Advances the internal pointer to the next node in the node list.
     * 
     * @return void
     */
    public function next()
    {
        return next($this->_node);
    }

    /**
     * Returns whether the internal pointer is still valid.
     *
     * It returns false when the end of list has been reached.
     *
     * @return bool
     */
    public function valid()
    {
        return $this->current() !== false;
    }

    /**
     * Returns if the current node has children
     * 
     * @return boolean
     */
    public function hasChildren()
    {
        return $this->current()->hasChildren();
    }

    /**
     * Return all children of current node
     * 
     * @return Tree_Composite_Iterator
     */
    public function getChildren()
    {
        return new Tree_Composite_Iterator($this->current());
    }

}