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
 * This node divides a workflow variable by a value.
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeVariableDiv extends ezcWorkflowNodeVariable
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
