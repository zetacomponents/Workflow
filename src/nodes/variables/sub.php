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
 * An object of the ezcWorkflowNodeVariableSub class subtracts a given value, either a
 * constant or the value of another workflow variable, from a specifled work?ow variable.
 *
 * <code>
 *  $sub = new ezcWorkflowNodeVariableSub (
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
class ezcWorkflowNodeVariableSub extends ezcWorkflowNodeArithmeticBase
{
    /**
     * Array with the name of the workflow variable and the value
     * that is subtracted from it.
     *
     * @var array
     */
    protected $configuration;

    /**
     * Perform variable modification.
     */
    protected function doExecute()
    {
        $this->variable -= $this->value;
    }

    /**
     * Returns a textual representation of this node.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->configuration['name'] . ' -= ' . $this->configuration['value'];
    }
}
?>
