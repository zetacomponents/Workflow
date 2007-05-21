<?php
/**
 * File containing the ezcWorkflowNodeExclusiveChoice class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * This node implements the Exclusive Choice workflow pattern.
 *
 * The Exclusive Choice workflow pattern defines multiple possible paths
 * for the workflow of which exactly one is chosen based on the conditions
 * set for the out nodes.
 *
 * Incomming nodes: 1
 * Outgoing nodes: 2..*
 *
 * @todo example
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeExclusiveChoice extends ezcWorkflowNodeConditionalBranch
{
    /**
     * Constraint: The minimum number of conditional outgoing nodes this node
     * has to have. Set to false to disable this constraint.
     *
     * @var integer
     */
    protected $minConditionalOutNodes = 2;

    /**
     * Constraint: The minimum number of conditional outgoing nodes this node
     * has to activate. Set to false to disable this constraint.
     *
     * @var integer
     */
    protected $minActivatedConditionalOutNodes = 1;

    /**
     * Constraint: The maximum number of conditional outgoing nodes this node
     * may activate. Set to false to disable this constraint.
     *
     * @var integer
     */
    protected $maxActivatedConditionalOutNodes = 1;

}
?>
