<?php
/**
 * File containing the ezcWorkflowNodeMultiChoice class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * This node implements the Multi-Choice workflow pattern.
 *
 * The Multi-Choice workflow pattern defines multiple possible paths for the workflow of
 * which one or more are chosen. It is a generalization of the Parallel Split and
 * Exclusive Choice workflow patterns.
 *
 * Incoming nodes: 1
 * Outgoing nodes: 2..*
 *
 * @todo example
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeMultiChoice extends ezcWorkflowNodeConditionalBranch
{
}
?>
