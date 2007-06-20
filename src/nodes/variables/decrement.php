<?php
/**
 * File containing the ezcWorkflowNodeVariableDecrement class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * This node decrements a workflow variable by one when executed.
 *
 * <code>
 *   $dec = new ezcWorkflowNodeVariableDecrement ( 'variable name' ) ;
 * </code>
 *
 * Incoming nodes: 1
 * Outgoing nodes: 1
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeVariableDecrement extends ezcWorkflowNodeArithmeticBase
{
    /**
     * The name of the variable to be decremented.
     *
     * @var string
     */
    protected $configuration;

    /**
     * Perform variable modification.
     */
    protected function doExecute()
    {
        $this->variable--;
    }

    /**
     * Returns a textual representation of this node.
     *
     * @return string
     * @ignore
     */
    public function __toString()
    {
        return $this->configuration . '--';
    }
}
?>
