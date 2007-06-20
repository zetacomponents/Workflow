<?php
/**
 * File containing the ezcWorkflowNodeVariableDiv class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Divides a workflow variable by another variable or a constant value.
 *
 * An object of the ezcWorkflowNodeVariableDiv class divides a specified workflow variable
 * by a given operand, either a constant or the value of another workflow variable.
 *
 * This example will divide the contents of the workflow variable 'wfVar' by five and put it
 * back into wfVar.
 * <code>
 *   $op = 5;
 *   $div = new ezcWorkflowNodeVariableDiv (
 *           array ( 'name' = > 'wfVar',
 *                   'operand' = > $op )
 *            );
 * </code>
 * If the operand is a string, the value of the workflow variable identified by that string is used.
 *
 * Incoming nodes: 1
 * Outgoing nodes: 1
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeVariableDiv extends ezcWorkflowNodeArithmeticBase
{
    /**
     * Perform variable modification.
     */
    protected function doExecute()
    {
        $this->variable /= $this->operand;
    }

    /**
     * Returns a textual representation of this node.
     *
     * @return string
     * @ignore
     */
    public function __toString()
    {
        return $this->configuration['name'] . ' /= ' . $this->configuration['operand'];
    }
}
?>
