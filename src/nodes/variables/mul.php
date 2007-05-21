<?php
/**
 * File containing the ezcWorkflowNodeVariableMul class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Multiplies a workflow variable with another variable or a constant value.
 *
 * An object of the ezcWorkflowNodeVariableMul class multiplies a specified workflow
 * variable with a given value, either a constant or the value of another workflow variable.
 *
 * <code>
 *   $mul = new ezcWorkflowNodeVariableMul (
 *           array ( 'name' = > 'variable name' , 'value' = > $value )
 *            );
 * </code>
 * If $value is a string, the value of the workflow variable identified by that string is used.
 *
 * Incomming nodes: 1
 * Outgoing nodes: 1
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeVariableMul extends ezcWorkflowNodeVariable
{
    /**
     * Array with the name of the workflow variable and the value
     * that it is multiplied with.
     *
     * @var array
     */
    protected $configuration;

    /**
     * Perform variable modification.
     */
    protected function doExecute()
    {
        $this->variable *= $this->value;
    }

    /**
     * Returns a textual representation of this node.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->configuration['name'] . ' *= ' . $this->configuration['value'];
    }
}
?>
