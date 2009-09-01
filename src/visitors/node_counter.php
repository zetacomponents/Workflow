<?php
/**
 * File containing the ezcWorkflowVisitorNodeCounter class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Visitor that counts the number of nodes in a workflow.
 *
 * @package Workflow
 * @version //autogen//
 * @ignore
 */
class ezcWorkflowVisitorNodeCounter extends ezcWorkflowVisitor
{
    /**
     * Constructor.
     *
     * @param ezcWorkflow $workflow
     */
    public function __construct( ezcWorkflow $workflow )
    {
        parent::__construct();
        $workflow->accept( $this );
    }

    /**
     * Returns the maximum node id.
     *
     * @return array
     */
    public function getNumNodes()
    {
        return count( $this->visited );
    }
}
?>
