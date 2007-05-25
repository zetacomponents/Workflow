<?php
/**
 * File containing the ezcWorkflowVisitorNodeCollector class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowVisitorNodeCollector implements ezcWorkflowVisitor
{
    /**
     * @var array
     */
    protected $nodes = array();

    /**
     * @param ezcWorkflow $workflow
     */
    public function __construct( ezcWorkflow $workflow )
    {
        $workflow->accept( $this );
    }

    /**
     * @param ezcWorkflowVisitable $visitable
     * @return boolean
     */
    public function visit( ezcWorkflowVisitable $visitable )
    {
        foreach ( $this->nodes as $node )
        {
            if ( $node === $visitable )
            {
                return false;
            }
        }

        if ( $visitable instanceof ezcWorkflowNode )
        {
            $this->nodes[] = $visitable;
        }

        return true;
    }

    /**
     * @return array
     */
    public function getNodes() {
        return $this->nodes;
    }
}
?>
