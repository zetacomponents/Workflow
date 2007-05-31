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
 * Divides a workflow variable with another variable or a constant value.
 *
 * An object of the ezcWorkflowNodeVariableDiv class divides a specified workflow variable
 * by a given value, either a constant or the value of another workflow variable.
 * <code>
 *   $div = new ezcWorkflowNodeVariableDiv (
 *           array ( 'name' = > 'variable name' , 'value' = > $value )
 *            );
 * </code>
 * If $value is a string, the value of the variable identi?ed by that string is used.
 *
 * Incomming nodes: 1
 * Outgoing nodes: 1
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeVariableDiv extends ezcWorkflowNodeArithmeticBase
{
    /**
     * Array with the name of the workflow variable and the value
     * that it is divided by.
     *
     * @var array
     */
    protected $configuration;

    /**
     * Perform variable modification.
     */
    protected function doExecute()
    {
        $this->variable /= $this->value;
    }

    /**
     * Returns a textual representation of this node.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->configuration['name'] . ' /= ' . $this->configuration['value'];
    }
}
?>
