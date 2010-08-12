<?php
/**
 * File containing the ezcWorkflowNodeVariableMul class.
 *
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 * 
 *   http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 *
 * @package Workflow
 * @version //autogen//
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

/**
 * Multiplies a workflow variable with another variable or a constant value.
 *
 * An object of the ezcWorkflowNodeVariableMul class multiplies a specified workflow
 * variable with a given operand, either a constant or the value of another workflow variable.
 *
 * This example will multiply the contents of the workflow variable 'wfVar' by five and put the
 * result in 'wfVar'.
 *
 * <code>
 * <?php
 * $mul = new ezcWorkflowNodeVariableMul(
 *   array( 'name' => 'wfVar', 'operand' => 5 )
 * );
 * ?>
 * </code>
 *
 * If the operand is a string, the value of the workflow variable identified by that string is used.
 *
 * Incoming nodes: 1
 * Outgoing nodes: 1
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeVariableMul extends ezcWorkflowNodeArithmeticBase
{
    /**
     * Perform variable modification.
     */
    protected function doExecute()
    {
        $this->variable *= $this->operand;
    }

    /**
     * Generate node configuration from XML representation.
     *
     * @param DOMElement $element
     * @return array
     * @ignore
     */
    public static function configurationFromXML( DOMElement $element )
    {
        return array(
          'name'    => $element->getAttribute( 'variable' ),
          'operand' => $element->getAttribute( 'operand' )
        );
    }

    /**
     * Generate XML representation of this node's configuration.
     *
     * @param DOMElement $element
     * @ignore
     */
    public function configurationToXML( DOMElement $element )
    {
        $element->setAttribute( 'variable', $this->configuration['name'] );
        $element->setAttribute( 'operand', $this->configuration['operand'] );
    }

    /**
     * Returns a textual representation of this node.
     *
     * @return string
     * @ignore
     */
    public function __toString()
    {
        return $this->configuration['name'] . ' *= ' . $this->configuration['operand'];
    }
}
?>
