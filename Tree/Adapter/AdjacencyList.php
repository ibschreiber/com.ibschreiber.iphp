<?php

require_once dirname(__FILE__) . '\Abstract.php';
require_once dirname(__FILE__) . '\..\Node.php';
require_once dirname(__FILE__) . '\..\Exception.php';

/**
 * A common representation of the adjacency list model which
 * consists of pairs of nodes, each pair represents a 
 * parent-child relationship.
 * 
 * @package 	Tree
 * @subpackage 	Adapter
 * @author 		andy.roberts
 */
class Tree_Adapter_AdjacencyList extends Tree_Adapter_Abstract
{
    protected $_array;

    /**
     * Constructor
     * 
     * @param array $array
     */
    public function __construct($array)
    {
        $this->_array = $array;
    }

    /**
     * Returns the node identified by the ID $id.
     *
     * @param string $id
     * @throws Tree_Exception if there is no node could be found
     * @return Tree_Node
     */
    public function fetchNodeById($id)
    {
        $node = array_values(array_filter($this->_array, create_function('$item', 'return $item[\'id\'] == ' . $id . ';')));
        
        if (! isset($node[0]['id'])) {
            $message = 'Node ID: ' . $id . ' could not be found.';
            throw new Tree_Exception($message);
        }
        
        return new Tree_Node($node[0]);
    }

    /**
     * Returns the parent node of the node
     *
     * @param string $nodeId
     * @return Tree_Composite_Node
     */
    public function fetchParent($id)
    {
        $node = $this->fetchNodeById($id);
        $parentNode = $node->getParent();
        return $parentNode !== null ? $this->fetchNodeById($parentNode->getId()) : null;
    }

    /**
     * Returns all the children of the node with ID $id.
     *
     * @param string $id
     * @return Tree_Node
     */
    public function fetchChildren($id)
    {
        $this->setRootNode($this->fetchNodeById($id));
        
        foreach ($this->_array as $item) {
            $this->_push($item);
        }
        
        return $this->getRootNode();
    }

    /*
     * Create node from $item and push onto composite tree structure
     * 
     * @param array $item
     */
    protected function _push($item)
    {   
        $node = new Tree_Node($item);
        
        if ($tree = $this->_traverse($this->getRootNode(), $node)) {
            $tree->attach($node);
        }
    }

    /*
     * Traverse the composite tree structure and attach each 
     * child node to the parent node.
     * 
     * @param Tree_Node $parent
     * @param Tree_Node $child
     */
    private function _traverse(Tree_Node $parent, Tree_Node $child)
    {
        if ($parent->hasChildren()) {
            foreach ($parent->getChildren() as $node) {
                if ($tree = $this->_traverse($node, $child)) {
                    $tree->attach($child);
                }
            }
        }
        
        if ($parent->getId() == $child->getParent()) {
            return $parent;
        }
    }
}