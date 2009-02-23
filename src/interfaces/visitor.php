<?php
/**
 * File containing the ezcWorkflowVisitor interface.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Interface for visitor implementations that want to process
 * a workflow using the Visitor design pattern.
 *
 * visit() is called on each of the nodes in the workflow in a top-down,
 * depth-first fashion.
 *
 * Start the processing of the workflow by calling accept() on the workflow
 * passing the visitor object as the sole parameter.
 *
 * @package Workflow
 * @version //autogen//
 */
interface ezcWorkflowVisitor
{
    /**
     * Visit the $visitable.
     *
     * Each node in the graph is visited once.
     *
     * @param ezcWorkflowVisitable $visitable
     * @return bool
     */
    public function visit( ezcWorkflowVisitable $visitable );
}
?>
