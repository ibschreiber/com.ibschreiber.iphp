<?php
/**
 * An abstract class for all tree graph implementations
 * 
 * @package Tree
 * @author 	andy.roberts
 */
abstract class Tree_Adapter_Abstract
{
    /**
     * Root node
     * 
     * @var Tree_Node
     */
    protected $_rootNode;

    /**
     * Set the root node.
     *
     * @param Tree_Node $node
     * @return void
     */
    public function setRootNode($node)
    {
        $this->_rootNode = $node;
    }

    /**
     * Returns the root node.
     *
     * @return Tree_Node
     */
    public function getRootNode()
    {
        return $this->_rootNode;
    }

    abstract public function fetchNodeById($id);
    abstract public function fetchParent($id);
    abstract public function fetchChildren($id);
}