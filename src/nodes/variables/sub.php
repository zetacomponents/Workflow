<?php
/**
 * File containing the ezcWorkflowNodeVariableSub class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Subtracts a workflow variable from another variable or a constant value.
 *
 * An object of the ezcWorkflowNodeVariableSub class subtracts a given operand, either a
 * constant or the value of another workflow variable, from a specifled workflow variable.
 *
 * <code>
 *  $sub = new ezcWorkflowNodeVariableSub (
 *           array ( 'name' = > 'variable name' , 'operand' = > $operand )
 *            );
 * </code>
 * If $value is a string, the value of the workflow variable identified by that string is used.
 *
 * Incoming nodes: 1
 * Outgoing nodes: 1
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeVariableSub extends ezcWorkflowNodeArithmeticBase
{
    /**
     * Perform variable modification.
     */
    protected function doExecute()
    {
        $this->variable -= $this->operand;
    }

    /**
     * Returns a textual representation of this node.
     *
     * @return string
     * @ignore
     */
    public function __toString()
    {
        return $this->configuration['name'] . ' -= ' . $this->configuration['operand'];
    }
}
?>
