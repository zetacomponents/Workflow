<?php
/**
 * File containing the ezcWorkflowNodeSynchronizingMerge class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * This node implements the Synchronizing Merge workflow pattern.
 *
 * The Synchronizing Merge workflow pattern is to be used to synchronize multiple parallel
 * threads of execution that are activated by a preceding Multi-Choice.
 *
 * Incomming nodes: 2..*
 * Outgoing nodes: 1
 *
 * @todo example
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeSynchronizingMerge extends ezcWorkflowNodeSynchronization
{
}
?>
