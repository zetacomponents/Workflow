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
 * This node multiplies a workflow variable with a value.
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
