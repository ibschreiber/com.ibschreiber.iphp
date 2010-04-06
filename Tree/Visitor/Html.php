<?php

require_once dirname(__FILE__) . '/Interface.php';

/**
 * An implementation of the Tree_Visitor interface that generates
 * a HTML representation of a tree structure.
 * 
 * @package 	Tree
 * @subpackage 	Visitor
 * @author 		andy.roberts
 */
class Tree_Visitor_Html implements Tree_Visitor_Interface
{
    /**
     * Array of nodes
     *
     * @var array
     */
    private $_nodes = array();

    /**
     * The root node of the tree
     *
     * @var string
     */
    private $_rootNode = null;

    /**
     * Visits each node of the tree
     *
     * @param Tree_Visitor_Visitable $visitable
     * @return bool
     */
    public function visit(Tree_Visitor_Visitable $visitable)
    {
        if ($visitable instanceof Tree_Composite_Interface) {
            if ($this->_rootNode === null) {
                $this->_rootNode = $visitable;
            }
            
            $parent = $visitable->getParent();
            
            if ($parent !== null) {
                $this->_nodes[$parent][$visitable->getId()] = $visitable;
            }
        }
        return true;
    }

    /**
     * This methods loops over all the node's children and adds the 
     * correct layout format for each node.
     *
     * @param string $id
     * @param string $ident
     *
     * @return string
     */
    private function _traverseChildren($id, $indent = 0)
    {
        $html = '';
        
        $childNodes = $this->_nodes[$id];
        $countNodes = count($childNodes);
        
        if ($countNodes > 0) {
            
            $html .= str_repeat('  ', $indent);
            $html .= "<ul>\n";
            
            foreach ($childNodes as $id => $node) {
                $html .= str_repeat('  ', $indent + 2);
                
                if (isset($this->_nodes[$id])) {
                    $html .= "<li>{$node->getName()}\n";
                    $html .= $this->_traverseChildren($id, $indent + 2);
                    $html .= str_repeat('  ', $indent + 2);
                    $html .= "</li>\n";
                } else {
                    $html .= "<li>{$node->getName()}</li>\n";
                }
            }
            
            $html .= str_repeat('  ', $indent);
            $html .= "</ul>\n";
        }
        
        return $html;
    }

    /**
     * Returns the HTML representation of the tree.
     *
     * @return string
     */
    public function __toString()
    {
        $tree = $this->_rootNode->getName() . "\n";
        ;
        $tree .= $this->_traverseChildren($this->_rootNode->getId());
        return $tree;
    }
}