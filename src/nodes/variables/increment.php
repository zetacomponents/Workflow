<?php
/**
 * File containing the ezcWorkflowNodeVariableIncrement class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * This node increments a workflow variable by one when executed..
 *
 * <code>
 *   $inc = new ezcWorkflowNodeVariableIncrement ( 'variable name' ) ;
 * </code>
 *
 * Incoming nodes: 1
 * Outgoing nodes: 1
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeVariableIncrement extends ezcWorkflowNodeArithmeticBase
{
    /**
     * The name of the variable to be incremented.
     *
     * @var string
     */
    protected $configuration;

    /**
     * Perform variable modification.
     */
    protected function doExecute()
    {
        $this->variable++;
    }

    /**
     * Returns a textual representation of this node.
     *
     * @return string
     * @ignore
     */
    public function __toString()
    {
        return $this->configuration . '++';
    }
}
?>
